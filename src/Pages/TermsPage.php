<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Pages;

use Illuminate\Contracts\Auth\Authenticatable;
use YezzMedia\Account\Pages\AccountPage;
use YezzMedia\Account\Support\AccountManager;
use YezzMedia\UserSupport\Support\LegalContentManager;

class TermsPage extends AccountPage
{
    protected static ?string $slug = 'legal-terms';

    protected string $view = 'user-support::account.terms';

    protected static \UnitEnum|string|null $navigationGroup = 'Legal';

    protected static ?string $navigationLabel = 'AGB';

    protected static \BackedEnum|string|null $navigationIcon = 'scale';

    protected static ?int $navigationSort = 30;

    protected function getViewData(): array
    {
        return $this->pageData(app(AccountManager::class)->currentUser());
    }

    protected function getPageTitle(): string
    {
        $manager = app(LegalContentManager::class);

        return $manager->title('terms') ?? 'AGB';
    }

    protected function getPageDescription(): string
    {
        return 'Allgemeine Geschäftsbedingungen.';
    }

    protected function pageData(?Authenticatable $user = null): array
    {
        $manager = app(LegalContentManager::class);

        return [
            'content' => $manager->content('terms'),
            'title' => $manager->title('terms') ?? 'AGB',
        ];
    }
}
