<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Pages;

use Illuminate\Contracts\Auth\Authenticatable;
use YezzMedia\Account\Pages\AccountPage;

class PrivacyPage extends AccountPage
{
    protected static ?string $slug = 'privacy';

    protected string $view = 'user-support::account.privacy';

    protected static \UnitEnum|string|null $navigationGroup = 'Legal';

    protected static ?string $navigationLabel = 'Datenschutz';

    protected static \BackedEnum|string|null $navigationIcon = 'shield-check';

    protected static ?int $navigationSort = 20;

    protected function getPageTitle(): string
    {
        return 'Datenschutzerklärung';
    }

    protected function getPageDescription(): string
    {
        return 'Informationen zur Verarbeitung personenbezogener Daten.';
    }

    protected function pageData(?Authenticatable $user = null): array
    {
        return [];
    }
}
