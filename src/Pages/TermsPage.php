<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Pages;

use Illuminate\Contracts\Auth\Authenticatable;
use YezzMedia\Account\Pages\AccountPage;

class TermsPage extends AccountPage
{
    protected static ?string $slug = 'terms';

    protected string $view = 'user-support::account.terms';

    protected static \UnitEnum|string|null $navigationGroup = 'Legal';

    protected static ?string $navigationLabel = 'AGB';

    protected static \BackedEnum|string|null $navigationIcon = 'scale';

    protected static ?int $navigationSort = 30;

    protected function getPageTitle(): string
    {
        return 'AGB';
    }

    protected function getPageDescription(): string
    {
        return 'Allgemeine Geschäftsbedingungen.';
    }

    protected function pageData(?Authenticatable $user = null): array
    {
        return [];
    }
}
