<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Install;

use RuntimeException;
use YezzMedia\Foundation\Data\InstallContext;
use YezzMedia\Foundation\Install\InstallStep;

final class PublishSupportConfigInstallStep implements InstallStep
{
    public function key(): string
    {
        return 'user-support.config.publish';
    }

    public function package(): string
    {
        return 'yezzmedia/laravel-user-support';
    }

    public function priority(): int
    {
        return 10;
    }

    public function shouldRun(InstallContext $context): bool
    {
        return $context->refreshPublishedResources || ! file_exists(config_path('user-support.php'));
    }

    public function handle(InstallContext $context): void
    {
        $source = __DIR__.'/../../config/user-support.php';

        if (! file_exists($source)) {
            throw new RuntimeException('Cannot publish user-support config: source file not found.');
        }

        copy($source, config_path('user-support.php'));
    }
}
