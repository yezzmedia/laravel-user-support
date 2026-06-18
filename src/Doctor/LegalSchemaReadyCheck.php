<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Doctor;

use Illuminate\Support\Facades\Schema;
use YezzMedia\Foundation\Data\DoctorResult;
use YezzMedia\Foundation\Doctor\DoctorCheck;

final class LegalSchemaReadyCheck implements DoctorCheck
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
        return 'user-support.legal.schema.ready';
    }

    public function package(): string
    {
        return 'yezzmedia/laravel-user-support';
    }

    public function run(): DoctorResult
    {
        if (! Schema::hasTable('legal_contents')) {
            return $this->result('warning', 'The legal_contents table is missing. Run the migrations.', true);
        }

        return $this->result('ok', 'The legal_contents table exists.', false);
    }
}
