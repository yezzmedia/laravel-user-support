<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;
use YezzMedia\UserSupport\Support\SupportTicketManager;

final class TicketDetailPage extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $slug = 'support/tickets/{ticket}';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'user-support::ops.ticket-detail-page';

    protected static ?string $title = 'Ticket';

    public ?string $replyBody = null;

    public array $detail = [];

    public function mount(string $ticket): void
    {
        $this->detail = app(SupportTicketManager::class)->ticketDetail((int) $ticket);
    }

    public static function canAccess(): bool
    {
        return Gate::check('support.manage');
    }

    public function reply(): void
    {
        $this->validate(['replyBody' => ['required', 'string', 'max:5000']]);

        $ticketId = (int) ($this->detail['id'] ?? 0);
        if ($ticketId === 0) {
            return;
        }

        SupportTicketManager::replyToTicket($ticketId, $this->replyBody ?? '', 'admin');
        $this->replyBody = null;
        $this->detail = app(SupportTicketManager::class)->ticketDetail($ticketId);
    }

    public function close(): void
    {
        $ticketId = (int) ($this->detail['id'] ?? 0);
        if ($ticketId === 0) {
            return;
        }

        SupportTicketManager::closeTicket($ticketId);
        $this->detail = app(SupportTicketManager::class)->ticketDetail($ticketId);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to list')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(TicketListPage::getUrl()),
        ];
    }

    public function getTitle(): string
    {
        $number = $this->detail['ticket_number'] ?? '';

        return $number !== '' ? "Ticket #{$number}" : 'Ticket';
    }
}
