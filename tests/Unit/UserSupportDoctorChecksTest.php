<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use YezzMedia\Foundation\Data\DoctorResult;
use YezzMedia\UserSupport\Doctor\SupportConfigPublishedCheck;
use YezzMedia\UserSupport\Doctor\SupportSchemaReadyCheck;

it('passes schema check when support_tickets table exists', function () {
    $result = app(SupportSchemaReadyCheck::class)->run();

    expect($result)->toBeInstanceOf(DoctorResult::class)
        ->and($result->status)->toBe('passed')
        ->and($result->isBlocking)->toBeFalse()
        ->and($result->key)->toBe('user-support.schema.ready');
});

it('fails schema check when support_tickets table is missing', function () {
    Schema::drop('support_tickets');
    Schema::drop('support_ticket_replies');

    $result = app(SupportSchemaReadyCheck::class)->run();

    expect($result->status)->toBe('warning')
        ->and($result->isBlocking)->toBeTrue();
});

it('passes config check when config file exists', function () {
    $path = config_path('user-support.php');
    if (! file_exists($path)) {
        file_put_contents($path, '<?php return [];');
    }

    $result = app(SupportConfigPublishedCheck::class)->run();

    expect($result)->toBeInstanceOf(DoctorResult::class)
        ->and($result->status)->toBe('passed')
        ->and($result->isBlocking)->toBeFalse()
        ->and($result->key)->toBe('user-support.config.published');
});

it('warns when config file is not published', function () {
    $path = config_path('user-support.php');
    $exists = file_exists($path);
    if ($exists) {
        unlink($path);
    }

    $result = app(SupportConfigPublishedCheck::class)->run();

    expect($result->status)->toBe('warning')
        ->and($result->isBlocking)->toBeFalse();

    if ($exists) {
        file_put_contents($path, '<?php return [];');
    }
});
