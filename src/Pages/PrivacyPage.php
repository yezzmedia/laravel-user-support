<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Pages;

use Illuminate\Contracts\Auth\Authenticatable;
use YezzMedia\Account\Pages\AccountPage;
use YezzMedia\Account\Support\AccountManager;
use YezzMedia\UserSupport\Support\LegalContentManager;

class PrivacyPage extends AccountPage
{
    protected static ?string $slug = 'legal-privacy';

    protected string $view = 'user-support::account.privacy';

    protected static \UnitEnum|string|null $navigationGroup = 'Legal';

    protected static ?string $navigationLabel = 'Datenschutz';

    protected static \BackedEnum|string|null $navigationIcon = 'shield-check';

    protected static ?int $navigationSort = 20;

    protected function getViewData(): array
    {
        return $this->pageData(app(AccountManager::class)->currentUser());
    }

    protected function getPageTitle(): string
    {
        $manager = app(LegalContentManager::class);

        return $manager->title('privacy') ?? 'Datenschutzerklärung';
    }

    protected function getPageDescription(): string
    {
        return 'Informationen zur Verarbeitung personenbezogener Daten.';
    }

    protected function pageData(?Authenticatable $user = null): array
    {
        $manager = app(LegalContentManager::class);

        return [
            'content' => $manager->content('privacy'),
            'title' => $manager->title('privacy') ?? 'Datenschutzerklärung',
        ];
    }
}
