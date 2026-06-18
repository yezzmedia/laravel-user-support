<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Doctor;

use YezzMedia\Foundation\Data\DoctorResult;
use YezzMedia\Foundation\Doctor\DoctorCheck;
use YezzMedia\UserSupport\Models\LegalContent;

final class LegalContentSeedCheck implements DoctorCheck
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
        return 'user-support.legal.seeded';
    }

    public function package(): string
    {
        return 'yezzmedia/laravel-user-support';
    }

    public function run(): DoctorResult
    {
        if (LegalContent::count() === 0) {
            return $this->result('warning', 'No legal content found. Run the seed install step.', false);
        }

        return $this->result('ok', 'Legal content has been seeded.', false);
    }
}
