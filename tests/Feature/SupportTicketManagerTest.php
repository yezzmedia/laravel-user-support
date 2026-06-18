<?php

declare(strict_types=1);

use Illuminate\Foundation\Auth\User;
use YezzMedia\UserSupport\Models\SupportTicket;
use YezzMedia\UserSupport\Support\SupportTicketManager;

it('creates a ticket for an authenticated user', function () {
    $user = User::create(['name' => 'Test User', 'email' => 'test@example.com']);

    $ticket = app(SupportTicketManager::class)->createTicket([
        'subject' => 'Help needed',
        'message' => 'I have an issue.',
    ], $user);

    expect($ticket)->toBeInstanceOf(SupportTicket::class)
        ->and($ticket->subject)->toBe('Help needed')
        ->and($ticket->message)->toBe('I have an issue.')
        ->and($ticket->status)->toBe('open')
        ->and($ticket->user_id)->toBe($user->getKey())
        ->and($ticket->guest_email)->toBeNull()
        ->and($ticket->guest_name)->toBeNull();
});

it('creates a ticket for a guest', function () {
    $ticket = app(SupportTicketManager::class)->createTicket([
        'name' => 'Guest User',
        'email' => 'guest@example.com',
        'subject' => 'Guest issue',
        'message' => 'I am a guest.',
    ], null);

    expect($ticket)->toBeInstanceOf(SupportTicket::class)
        ->and($ticket->subject)->toBe('Guest issue')
        ->and($ticket->user_id)->toBeNull()
        ->and($ticket->guest_email)->toBe('guest@example.com')
        ->and($ticket->guest_name)->toBe('Guest User');
});

it('returns tickets for a given user', function () {
    $user = User::create(['name' => 'Alice', 'email' => 'alice@example.com']);

    app(SupportTicketManager::class)->createTicket(['subject' => 'Ticket A', 'message' => 'Msg A'], $user);
    app(SupportTicketManager::class)->createTicket(['subject' => 'Ticket B', 'message' => 'Msg B'], $user);

    $tickets = app(SupportTicketManager::class)->userTickets($user);

    expect($tickets)->toHaveCount(2)
        ->and($tickets[0])->toHaveKeys(['id', 'ticket_number', 'subject', 'status', 'reply_count', 'created_at']);
});

it('returns empty array for user without tickets', function () {
    $user = User::create(['name' => 'Bob', 'email' => 'bob@example.com']);

    $tickets = app(SupportTicketManager::class)->userTickets($user);

    expect($tickets)->toBe([]);
});

it('returns all tickets via allTickets', function () {
    $user = User::create(['name' => 'Charlie', 'email' => 'charlie@example.com']);

    app(SupportTicketManager::class)->createTicket(['subject' => 'Ticket 1', 'message' => 'Msg 1'], $user);
    app(SupportTicketManager::class)->createTicket(['subject' => 'Ticket 2', 'message' => 'Msg 2'], null);

    $all = SupportTicketManager::allTickets();

    expect($all)->toHaveCount(2)
        ->and($all[0])->toHaveKeys(['id', 'ticket_number', 'user_name', 'user_email', 'subject', 'status', 'created_at']);
});

it('returns ticket detail with conversation', function () {
    $user = User::create(['name' => 'Dave', 'email' => 'dave@example.com']);

    $ticket = app(SupportTicketManager::class)->createTicket(['subject' => 'Detail test', 'message' => 'Initial message'], $user);

    SupportTicketManager::replyToTicket((int) $ticket->getKey(), 'Admin reply', 'admin');

    $detail = app(SupportTicketManager::class)->ticketDetail((int) $ticket->getKey());

    expect($detail)->toHaveKeys(['id', 'ticket_number', 'subject', 'status', 'can_reply', 'conversation'])
        ->and($detail['conversation'])->toHaveCount(2)
        ->and($detail['conversation'][0]['body'])->toBe('Initial message')
        ->and($detail['conversation'][1]['body'])->toBe('Admin reply')
        ->and($detail['can_reply'])->toBeTrue();
});

it('marks closed tickets as not replyable', function () {
    $user = User::create(['name' => 'Eve', 'email' => 'eve@example.com']);

    $ticket = app(SupportTicketManager::class)->createTicket(['subject' => 'Closed ticket', 'message' => 'Bye'], $user);
    SupportTicketManager::closeTicket((int) $ticket->getKey());

    $detail = app(SupportTicketManager::class)->ticketDetail((int) $ticket->getKey());

    expect($detail['status'])->toBe('closed')
        ->and($detail['can_reply'])->toBeFalse();
});

it('creates a reply and updates ticket status', function () {
    $user = User::create(['name' => 'Frank', 'email' => 'frank@example.com']);

    $ticket = app(SupportTicketManager::class)->createTicket(['subject' => 'Reply test', 'message' => 'Hello'], $user);

    $reply = SupportTicketManager::replyToTicket((int) $ticket->getKey(), 'Admin response', 'admin');

    expect($reply->body)->toBe('Admin response')
        ->and($reply->sender_type)->toBe('admin');

    $detail = app(SupportTicketManager::class)->ticketDetail((int) $ticket->getKey());
    expect($detail['status'])->toBe('replied');
});

it('can close a ticket and it stays closed', function () {
    $user = User::create(['name' => 'Grace', 'email' => 'grace@example.com']);

    $ticket = app(SupportTicketManager::class)->createTicket(['subject' => 'Close test', 'message' => 'Close me'], $user);
    SupportTicketManager::closeTicket((int) $ticket->getKey());

    expect(SupportTicket::query()->whereKey($ticket->getKey())->value('status'))->toBe('closed');
});

it('returns total unread count', function () {
    $user = User::create(['name' => 'Heidi', 'email' => 'heidi@example.com']);

    app(SupportTicketManager::class)->createTicket(['subject' => 'Open 1', 'message' => 'A'], $user);
    app(SupportTicketManager::class)->createTicket(['subject' => 'Open 2', 'message' => 'B'], $user);

    expect(SupportTicketManager::totalUnread())->toBe(2);
});

it('formats ticket numbers correctly', function () {
    expect(SupportTicketManager::formatTicketNumber(1))->toBe('10000001')
        ->and(SupportTicketManager::formatTicketNumber(999))->toBe('10000999');
});

it('returns fallback on ticket detail for non-existent ticket', function () {
    $detail = app(SupportTicketManager::class)->ticketDetail(99999);

    expect($detail['id'])->toBeNull()
        ->and($detail['conversation'])->toBe([]);
});
