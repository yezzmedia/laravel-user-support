<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Install;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use YezzMedia\Foundation\Data\InstallContext;
use YezzMedia\Foundation\Install\InstallStep;
use YezzMedia\UserSupport\Models\LegalContent;

final class SeedLegalContentInstallStep implements InstallStep
{
    public function key(): string
    {
        return 'user-support.legal.seed';
    }

    public function package(): string
    {
        return 'yezzmedia/laravel-user-support';
    }

    public function priority(): int
    {
        return 40;
    }

    public function shouldRun(InstallContext $context): bool
    {
        if (! Schema::hasTable('legal_contents')) {
            return false;
        }

        return LegalContent::count() === 0;
    }

    public function handle(InstallContext $context): void
    {
        Artisan::call('db:seed', ['--class' => 'YezzMedia\\UserSupport\\Database\\Seeders\\LegalContentSeeder']);
    }
}
