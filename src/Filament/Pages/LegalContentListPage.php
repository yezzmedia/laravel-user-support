<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use UnitEnum;
use YezzMedia\UserSupport\Models\LegalContent;

final class LegalContentListPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = 'Legal';

    protected static ?string $navigationLabel = 'Legal Content';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Legal Content';

    protected string $view = 'user-support::ops.legal-content-list-page';

    protected static ?string $slug = 'legal-content';

    public static function canAccess(): bool
    {
        return Gate::check('support.manage');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Legal Content')
            ->description('Manage legal pages content (Impressum, Privacy, Terms).')
            ->query(LegalContent::query())
            ->defaultSort('key', 'asc')
            ->paginated([10, 25, 50])
            ->columns([
                TextColumn::make('key')
                    ->label('Page')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('locale')
                    ->label('Locale')
                    ->sortable()
                    ->width(60),
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('published')
                    ->label('')
                    ->width(30)
                    ->icon(fn (bool $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                TextColumn::make('published')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Published' : 'Draft')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()
                    ->url(fn (LegalContent $record): string => LegalContentEditPage::getUrl(['record' => $record->id])),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No legal content yet.')
            ->emptyStateDescription('Legal content will appear here after running the seeder.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
