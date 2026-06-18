<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Install;

use Illuminate\Support\Facades\Schema;
use RuntimeException;
use YezzMedia\Foundation\Data\InstallContext;
use YezzMedia\Foundation\Install\InstallStep;

final class EnsureSupportSchemaReadyInstallStep implements InstallStep
{
    public function key(): string
    {
        return 'user-support.schema.ensure';
    }

    public function package(): string
    {
        return 'yezzmedia/laravel-user-support';
    }

    public function priority(): int
    {
        return 20;
    }

    public function shouldRun(InstallContext $context): bool
    {
        return ! Schema::hasTable('support_tickets');
    }

    public function handle(InstallContext $context): void
    {
        if (! $context->allowMigrations) {
            throw new RuntimeException('The support ticket tables are not ready. Run `php artisan migrate` or rerun with --migrate flag.');
        }
    }
}
