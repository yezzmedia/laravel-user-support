<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use YezzMedia\UserSupport\Filament\Pages\TicketDetailPage;
use YezzMedia\UserSupport\Filament\Pages\TicketListPage;

final class UserSupportOpsPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'user-support-ops';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            TicketListPage::class,
            TicketDetailPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}
