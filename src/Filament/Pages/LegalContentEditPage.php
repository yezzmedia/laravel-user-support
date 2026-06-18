<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use YezzMedia\UserSupport\Models\LegalContent;

final class LegalContentEditPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $slug = 'legal-content/{record}';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'user-support::ops.legal-content-edit-page';

    protected static ?string $title = 'Edit Legal Content';

    public ?array $data = [];

    public LegalContent $record;

    public function mount(LegalContent $record): void
    {
        $this->record = $record;
        $this->form->fill($this->record->toArray());
    }

    public static function canAccess(): bool
    {
        return Gate::check('support.manage');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Page Key')
                    ->required()
                    ->maxLength(50)
                    ->disabled(),
                Select::make('locale')
                    ->label('Locale')
                    ->options([
                        'de' => 'Deutsch',
                        'en' => 'English',
                    ])
                    ->required()
                    ->disabled(),
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('content')
                    ->label('Content (Markdown)')
                    ->required()
                    ->rows(20)
                    ->helperText('Supports Markdown formatting. HTML is stripped for security.'),
                Toggle::make('published')
                    ->label('Published'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'published' => $data['published'],
            'updated_by' => Auth::id(),
        ]);

        $this->record->refresh();

        Notification::make()
            ->title('Legal content saved.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to list')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(LegalContentListPage::getUrl(panel: 'ops')),
            Action::make('save')
                ->label('Save')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->action('save'),
        ];
    }

    public function getTitle(): string
    {
        $key = $this->record->key ?? '';

        return $key !== '' ? "Edit: {$this->record->locale} / {$key}" : 'Edit Legal Content';
    }
}
