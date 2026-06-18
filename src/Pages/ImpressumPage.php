<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Pages;

use Illuminate\Contracts\Auth\Authenticatable;
use YezzMedia\Account\Pages\AccountPage;
use YezzMedia\UserSupport\Support\LegalContentManager;

class ImpressumPage extends AccountPage
{
    protected static ?string $slug = 'impressum';

    protected string $view = 'user-support::account.impressum';

    protected static \UnitEnum|string|null $navigationGroup = 'Legal';

    protected static ?string $navigationLabel = 'Impressum';

    protected static \BackedEnum|string|null $navigationIcon = 'document-text';

    protected static ?int $navigationSort = 10;

    protected function getPageTitle(): string
    {
        $manager = app(LegalContentManager::class);

        return $manager->title('impressum') ?? 'Impressum';
    }

    protected function getPageDescription(): string
    {
        return 'Rechtliche Angaben gemäß § 5 TMG.';
    }

    protected function pageData(?Authenticatable $user = null): array
    {
        $manager = app(LegalContentManager::class);

        return [
            'content' => $manager->content('impressum'),
            'title' => $manager->title('impressum') ?? 'Impressum',
        ];
    }
}
