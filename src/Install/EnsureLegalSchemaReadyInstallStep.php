<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Install;

use Illuminate\Support\Facades\Schema;
use YezzMedia\Foundation\Data\InstallContext;
use YezzMedia\Foundation\Install\InstallStep;

final class EnsureLegalSchemaReadyInstallStep implements InstallStep
{
    public function key(): string
    {
        return 'user-support.legal.schema.ensure';
    }

    public function package(): string
    {
        return 'yezzmedia/laravel-user-support';
    }

    public function priority(): int
    {
        return 30;
    }

    public function shouldRun(InstallContext $context): bool
    {
        return ! Schema::hasTable('legal_contents');
    }

    public function handle(InstallContext $context): void
    {
        if (! $context->allowMigrations) {
            throw new \RuntimeException('The legal_contents table is not ready. Run `php artisan migrate` or rerun with --migrate flag.');
        }
    }
}
