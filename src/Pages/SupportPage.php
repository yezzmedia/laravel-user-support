<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Pages;

use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable;
use YezzMedia\Account\Pages\AccountPage;
use YezzMedia\UserSupport\Support\SupportTicketManager;

class SupportPage extends AccountPage
{
    protected static ?string $slug = 'support';

    protected string $view = 'user-support::account.support-page';

    protected static \UnitEnum|string|null $navigationGroup = 'Account';

    protected static ?string $navigationLabel = 'Support';

    protected static \BackedEnum|string|null $navigationIcon = 'help-circle';

    protected static ?int $navigationSort = 6;

    public ?array $data = [];

    public ?string $replyBody = null;

    public ?int $expandedTicketId = null;

    /** @var list<array{body: string, sender_type: string, created_at: string, created_at_human: string}> */
    public array $expandedConversation = [];

    public function mount(): void
    {
        $this->data = [
            'subject' => '',
            'message' => '',
        ];
    }

    public function sendContact(): void
    {
        validator($this->data, [
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ])->validate();

        $user = auth()->user();

        if ($user === null) {
            return;
        }

        app(SupportTicketManager::class)->createTicket([
            'name' => $user->name ?? '',
            'email' => $user->email ?? '',
            'subject' => $this->data['subject'] ?? '',
            'message' => $this->data['message'] ?? '',
        ], $user);

        $this->data = ['subject' => '', 'message' => ''];

        Notification::make()
            ->success()
            ->title('Message sent')
            ->body('Your message has been submitted.')
            ->send();
    }

    public function toggleTicket(int $ticketId): void
    {
        if ($this->expandedTicketId === $ticketId) {
            $this->expandedTicketId = null;
            $this->expandedConversation = [];

            return;
        }

        $user = auth()->user();

        if ($user === null) {
            return;
        }

        $detail = app(SupportTicketManager::class)->ticketDetail($ticketId);
        $this->expandedTicketId = $ticketId;
        $this->expandedConversation = $detail['conversation'];
    }

    public function closeTicket(int $ticketId): void
    {
        SupportTicketManager::closeTicket($ticketId);
        $this->expandedTicketId = null;
        $this->expandedConversation = [];

        Notification::make()
            ->success()
            ->title('Ticket closed')
            ->body('The ticket has been closed successfully.')
            ->send();
    }

    public function replyToTicket(int $ticketId): void
    {
        $this->validate([
            'replyBody' => ['required', 'string', 'max:5000'],
        ]);

        $user = auth()->user();

        if ($user === null) {
            return;
        }

        SupportTicketManager::replyToTicket($ticketId, $this->replyBody ?? '', 'user');
        $this->replyBody = null;

        $detail = app(SupportTicketManager::class)->ticketDetail($ticketId);
        $this->expandedConversation = $detail['conversation'];

        Notification::make()
            ->success()
            ->title('Reply sent')
            ->body('Your reply has been added.')
            ->send();
    }

    protected function getPageTitle(): string
    {
        return 'Help & Support';
    }

    protected function getPageDescription(): string
    {
        return 'Contact us or track your existing support tickets.';
    }

    protected function pageData(?Authenticatable $user = null): array
    {
        return [];
    }

    protected function getViewData(): array
    {
        $user = auth()->user();
        $tickets = [];

        if ($user !== null) {
            $tickets = app(SupportTicketManager::class)->userTickets($user);
        }

        return [
            'pageData' => [],
            'tickets' => $tickets,
        ];
    }
}
