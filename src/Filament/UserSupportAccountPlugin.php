<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use YezzMedia\UserSupport\Pages\ImpressumPage;
use YezzMedia\UserSupport\Pages\PrivacyPage;
use YezzMedia\UserSupport\Pages\SupportPage;
use YezzMedia\UserSupport\Pages\TermsPage;

final class UserSupportAccountPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'user-support-account';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            SupportPage::class,
            ImpressumPage::class,
            PrivacyPage::class,
            TermsPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}
