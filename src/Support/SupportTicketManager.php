<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Model;
use YezzMedia\UserSupport\Models\SupportTicket;
use YezzMedia\UserSupport\Models\SupportTicketReply;

final class SupportTicketManager
{
    /**
     * @throws MassAssignmentException
     */
    public function createTicket(array $data, ?Authenticatable $user = null): SupportTicket
    {
        $isGuest = $user === null;

        /** @var SupportTicket $ticket */
        $ticket = SupportTicket::query()->create([
            'user_id' => $isGuest ? null : ($user instanceof Model ? $user->getKey() : null),
            'guest_email' => $isGuest ? ($data['email'] ?? null) : null,
            'guest_name' => $isGuest ? ($data['name'] ?? null) : null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'open',
        ]);

        try {
            activity('support')
                ->causedBy($user)
                ->performedOn($ticket)
                ->event('created')
                ->log('Support ticket created');
        } catch (\Throwable) {
            // logging is optional
        }

        return $ticket;
    }

    /**
     * @return list<array{id: mixed, ticket_number: string, subject: string, status: string, reply_count: int, last_reply_at: ?string, created_at: string}>
     */
    public function userTickets(Authenticatable $user): array
    {
        $userId = $user->getKey();

        try {
            return SupportTicket::query()
                ->where('user_id', $userId)
                ->withCount('replies')
                ->latest()
                ->get()
                ->map(function (SupportTicket $ticket): array {
                    $lastReply = $ticket->replies()->latest()->first();

                    return [
                        'id' => $ticket->getKey(),
                        'ticket_number' => static::formatTicketNumber((int) $ticket->getKey()),
                        'subject' => $ticket->subject,
                        'status' => $ticket->status,
                        'reply_count' => $ticket->replies_count,
                        'last_reply_at' => $lastReply?->created_at?->diffForHumans(),
                        'created_at' => $ticket->created_at?->toIso8601String() ?? '',
                    ];
                })
                ->all();
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * @return list<array{id: mixed, ticket_number: string, user_name: string, user_email: string, subject: string, message: string, status: string, created_at: string}>
     */
    public static function allTickets(): array
    {
        if (! class_exists(SupportTicket::class)) {
            return [];
        }

        try {
            return SupportTicket::query()
                ->with('user')
                ->latest()
                ->get()
                ->map(static function (SupportTicket $ticket): array {
                    $user = $ticket->relationLoaded('user') ? $ticket->user : null;
                    $name = $user?->name ?? $ticket->guest_name ?? 'Unknown';
                    $email = $user?->email ?? $ticket->guest_email ?? '';

                    return [
                        'id' => $ticket->getKey(),
                        'ticket_number' => self::formatTicketNumber((int) $ticket->getKey()),
                        'user_name' => $name,
                        'user_email' => $email,
                        'subject' => $ticket->subject,
                        'message' => $ticket->message,
                        'status' => $ticket->status,
                        'created_at' => $ticket->created_at?->toIso8601String() ?? '',
                    ];
                })
                ->all();
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * @return array{id: mixed, ticket_number: string, subject: string, status: string, can_reply: bool, user_name: string, user_email: string, user_id: mixed, guest_name: ?string, guest_email: ?string, created_at: string, conversation: list<array{body: string, sender_type: string, created_at: string, created_at_human: string}>}
     */
    public function ticketDetail(int $ticketId): array
    {
        try {
            $ticket = SupportTicket::query()->with('user', 'replies')->findOrFail($ticketId);
            $user = $ticket->relationLoaded('user') ? $ticket->user : null;
            $name = $user?->name ?? $ticket->guest_name ?? 'Unknown';
            $email = $user?->email ?? $ticket->guest_email ?? '';

            $conversation = [];

            $conversation[] = [
                'body' => $ticket->message,
                'sender_type' => 'user',
                'created_at' => $ticket->created_at?->toIso8601String() ?? '',
                'created_at_human' => $ticket->created_at?->diffForHumans() ?? '',
            ];

            if ($ticket->relationLoaded('replies')) {
                foreach ($ticket->replies as $reply) {
                    $conversation[] = [
                        'body' => $reply->body,
                        'sender_type' => $reply->sender_type,
                        'created_at' => $reply->created_at?->toIso8601String() ?? '',
                        'created_at_human' => $reply->created_at?->diffForHumans() ?? '',
                    ];
                }
            }

            return [
                'id' => $ticket->getKey(),
                'ticket_number' => self::formatTicketNumber((int) $ticket->getKey()),
                'subject' => $ticket->subject,
                'status' => $ticket->status,
                'can_reply' => $ticket->status !== 'closed',
                'user_name' => $name,
                'user_email' => $email,
                'user_id' => $ticket->user_id,
                'guest_name' => $ticket->guest_name,
                'guest_email' => $ticket->guest_email,
                'created_at' => $ticket->created_at?->toIso8601String() ?? '',
                'conversation' => $conversation,
            ];
        } catch (\Throwable) {
            return [
                'id' => null, 'ticket_number' => '', 'subject' => '', 'status' => '', 'can_reply' => false,
                'user_name' => '', 'user_email' => '', 'user_id' => null,
                'guest_name' => null, 'guest_email' => null, 'created_at' => '', 'conversation' => [],
            ];
        }
    }

    public static function replyToTicket(int $ticketId, string $body, string $senderType = 'admin'): SupportTicketReply
    {
        /** @var SupportTicketReply $reply */
        $reply = SupportTicketReply::query()->create([
            'support_ticket_id' => $ticketId,
            'body' => $body,
            'sender_type' => $senderType,
        ]);

        $current = SupportTicket::query()->find($ticketId);
        if ($current && $current->status !== 'closed') {
            $newStatus = $senderType === 'admin' ? 'replied' : 'open';
            SupportTicket::query()->whereKey($ticketId)->update(['status' => $newStatus]);
        }

        return $reply;
    }

    public static function closeTicket(int $ticketId): void
    {
        if (! class_exists(SupportTicket::class)) {
            return;
        }

        try {
            SupportTicket::query()->whereKey($ticketId)->update(['status' => 'closed']);
        } catch (\Throwable) {
            // best-effort
        }
    }

    public static function totalUnread(): int
    {
        if (! class_exists(SupportTicket::class)) {
            return 0;
        }

        try {
            return SupportTicket::query()->where('status', 'open')->count();
        } catch (\Throwable) {
            return 0;
        }
    }

    public static function formatTicketNumber(int $id): string
    {
        $start = (int) config('user-support.tickets.ticket_number_start', 10000000);

        return (string) ($start + $id);
    }
}
