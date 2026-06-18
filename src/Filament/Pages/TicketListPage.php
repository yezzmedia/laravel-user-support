<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use UnitEnum;
use YezzMedia\UserSupport\Support\SupportTicketManager;

final class TicketListPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static string|UnitEnum|null $navigationGroup = 'Support';

    protected static ?string $navigationLabel = 'Tickets';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Support Tickets';

    protected string $view = 'user-support::ops.ticket-list-page';

    protected static ?string $slug = 'support/tickets';

    public static function canAccess(): bool
    {
        return Gate::check('support.manage');
    }

    public static function getNavigationBadge(): ?string
    {
        $open = SupportTicketManager::totalUnread();

        return $open > 0 ? (string) $open : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return SupportTicketManager::totalUnread() > 0 ? 'warning' : null;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Support Tickets')
            ->description('All support tickets from registered users and public contact form.')
            ->records(fn (): array => SupportTicketManager::allTickets())
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->columns([
                IconColumn::make('status')
                    ->label('')
                    ->width(20)
                    ->icon(fn ($state): string => match ($state) {
                        'open' => 'heroicon-o-envelope',
                        'replied' => 'heroicon-o-arrow-uturn-left',
                        'closed' => 'heroicon-o-check-circle',
                        default => 'heroicon-o-envelope-open',
                    })
                    ->color(fn ($state): string => match ($state) {
                        'open' => 'warning', 'replied' => 'info', 'closed' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('ticket_number')
                    ->label('Ticket')
                    ->prefix('#')
                    ->searchable(),
                TextColumn::make('user_name')->label('From')->searchable()->sortable(),
                TextColumn::make('user_email')->label('Email')->searchable()->sortable(),
                TextColumn::make('subject')->label('Subject')->searchable()->sortable()->wrap(),
                TextColumn::make('created_at')->label('Date')->dateTime('M j, Y H:i')->sortable(),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'open' => 'Open', 'replied' => 'Replied', 'closed' => 'Closed',
                        default => (string) $state,
                    })
                    ->color(fn ($state): string => match ($state) {
                        'Open' => 'warning', 'Replied' => 'info', 'Closed' => 'success',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record): string => TicketDetailPage::getUrl(['ticket' => $record['id']], panel: 'ops')),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No tickets yet.')
            ->emptyStateDescription('Support tickets will appear here.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-ellipsis');
    }
}
