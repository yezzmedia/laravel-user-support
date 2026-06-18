<?php

declare(strict_types=1);

use Illuminate\Foundation\Auth\User;
use YezzMedia\UserSupport\Pages\SupportPage;
use YezzMedia\UserSupport\Support\SupportTicketManager;

it('can be instantiated', function () {
    $page = app(SupportPage::class);

    expect($page)->toBeInstanceOf(SupportPage::class);
});

it('has correct navigation properties', function () {
    $ref = new ReflectionClass(SupportPage::class);

    $getStatic = function (string $prop) use ($ref): mixed {
        $p = $ref->getProperty($prop);
        $p->setAccessible(true);

        return $p->getValue(null);
    };

    expect($getStatic('navigationLabel'))->toBe('Support')
        ->and($getStatic('navigationSort'))->toBe(6)
        ->and((string) $getStatic('navigationIcon'))->toBe('help-circle');
});

it('mounts with empty data', function () {
    $page = app(SupportPage::class);
    $page->mount();

    expect($page->data)->toBe(['subject' => '', 'message' => ''])
        ->and($page->replyBody)->toBeNull()
        ->and($page->expandedTicketId)->toBeNull();
});

it('creates a ticket via sendContact', function () {
    $user = User::create([
        'name' => 'Support Tester',
        'email' => 'support-tester@example.com',
    ]);
    $this->actingAs($user);

    $page = app(SupportPage::class);
    $page->mount();

    $page->data = ['subject' => 'Test question', 'message' => 'I need help'];

    expect($page->data['subject'])->toBe('Test question')
        ->and($page->data['message'])->toBe('I need help');

    $tickets = app(SupportTicketManager::class)->userTickets($user);
    expect($tickets)->toHaveCount(0);

    $page->sendContact();

    $tickets = app(SupportTicketManager::class)->userTickets($user);
    expect($tickets)->toHaveCount(1)
        ->and($tickets[0]['subject'])->toBe('Test question');
});

it('resets form after sendContact', function () {
    $user = User::create([
        'name' => 'Reset Tester',
        'email' => 'reset-tester@example.com',
    ]);
    $this->actingAs($user);

    $page = app(SupportPage::class);
    $page->mount();
    $page->data = ['subject' => 'Reset test', 'message' => 'Should reset'];
    $page->sendContact();

    expect($page->data)->toBe(['subject' => '', 'message' => '']);
});

it('toggleTicket expands and collapses conversation', function () {
    $user = User::create([
        'name' => 'Toggle Tester',
        'email' => 'toggle-tester@example.com',
    ]);
    $this->actingAs($user);

    $ticket = app(SupportTicketManager::class)->createTicket([
        'subject' => 'Toggle test',
        'message' => 'Conversation starter',
    ], $user);

    $page = app(SupportPage::class);
    $page->mount();

    $ticketId = (int) $ticket->getKey();
    expect($page->expandedTicketId)->toBeNull();

    $page->toggleTicket($ticketId);
    expect($page->expandedTicketId)->toBe($ticketId)
        ->and($page->expandedConversation)->toHaveCount(1);

    $page->toggleTicket($ticketId);
    expect($page->expandedTicketId)->toBeNull();
});

it('returns tickets in getViewData', function () {
    $user = User::create([
        'name' => 'ViewData Tester',
        'email' => 'viewdata-tester@example.com',
    ]);
    $this->actingAs($user);

    app(SupportTicketManager::class)->createTicket([
        'subject' => 'View test',
        'message' => 'Check view data',
    ], $user);

    $page = app(SupportPage::class);
    $page->mount();

    $viewData = Closure::bind(fn () => $this->getViewData(), $page, SupportPage::class)();

    expect($viewData)->toHaveKeys(['pageData', 'tickets'])
        ->and($viewData['tickets'])->toHaveCount(1);
});
