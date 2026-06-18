<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Doctor;

use YezzMedia\Foundation\Data\DoctorResult;
use YezzMedia\Foundation\Doctor\DoctorCheck;

final class SupportConfigPublishedCheck implements DoctorCheck
{
    private function result(string $status, string $message, bool $isBlocking, ?array $context = null): DoctorResult
    {
        return new DoctorResult(
            key: $this->key(),
            package: $this->package(),
            status: $status,
            message: $message,
            isBlocking: $isBlocking,
            context: $context,
        );
    }

    public function key(): string
    {
        return 'user-support.config.published';
    }

    public function package(): string
    {
        return 'yezzmedia/laravel-user-support';
    }

    public function run(): DoctorResult
    {
        if (! file_exists(config_path('user-support.php'))) {
            return $this->result('warning', 'User-support config has not been published.', false);
        }

        return $this->result('ok', 'User-support config is published.', false);
    }
}
