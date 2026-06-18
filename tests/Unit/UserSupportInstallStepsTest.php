<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use YezzMedia\Foundation\Data\InstallContext;
use YezzMedia\UserSupport\Install\EnsureSupportSchemaReadyInstallStep;
use YezzMedia\UserSupport\Install\PublishSupportConfigInstallStep;

it('ensure schema step should run when table missing', function () {
    Schema::drop('support_tickets');
    Schema::drop('support_ticket_replies');

    $context = new InstallContext;
    $step = app(EnsureSupportSchemaReadyInstallStep::class);

    expect($step->shouldRun($context))->toBeTrue();
});

it('ensure schema step should not run when table exists', function () {
    $context = new InstallContext;
    $step = app(EnsureSupportSchemaReadyInstallStep::class);

    expect($step->shouldRun($context))->toBeFalse();
});

it('ensure schema step throws when migrations not allowed', function () {
    Schema::drop('support_tickets');
    Schema::drop('support_ticket_replies');

    $context = new InstallContext(allowMigrations: false);
    $step = app(EnsureSupportSchemaReadyInstallStep::class);

    expect(fn () => $step->handle($context))->toThrow(RuntimeException::class, 'not ready');
});

it('ensure schema step does not throw when migrations allowed', function () {
    Schema::drop('support_tickets');
    Schema::drop('support_ticket_replies');

    $context = new InstallContext(allowMigrations: true);
    $step = app(EnsureSupportSchemaReadyInstallStep::class);

    $step->handle($context);

    expect(true)->toBeTrue();
});

it('publish config step should run when refreshing', function () {
    $configPath = config_path('user-support.php');
    $exists = file_exists($configPath);
    if ($exists) {
        unlink($configPath);
    }

    $context = new InstallContext(refreshPublishedResources: true);
    $step = app(PublishSupportConfigInstallStep::class);

    expect($step->shouldRun($context))->toBeTrue();

    if (! $exists) {
        touch($configPath);
    }
});

it('publish config step should run when file missing', function () {
    $configPath = config_path('user-support.php');
    if (file_exists($configPath)) {
        unlink($configPath);
    }

    $context = new InstallContext(refreshPublishedResources: false);
    $step = app(PublishSupportConfigInstallStep::class);

    expect($step->shouldRun($context))->toBeTrue();
});

it('publish config step should not run when file exists and not refreshing', function () {
    $configPath = config_path('user-support.php');
    $exists = file_exists($configPath);
    if (! $exists) {
        touch($configPath);
    }

    $context = new InstallContext(refreshPublishedResources: false);
    $step = app(PublishSupportConfigInstallStep::class);

    expect($step->shouldRun($context))->toBeFalse();
});

it('publish config step copies config file', function () {
    $configPath = config_path('user-support.php');
    if (file_exists($configPath)) {
        unlink($configPath);
    }

    $context = new InstallContext(refreshPublishedResources: true);
    $step = app(PublishSupportConfigInstallStep::class);

    $step->handle($context);

    expect(file_exists($configPath))->toBeTrue();
});

it('has correct key and priority on ensure schema step', function () {
    $step = app(EnsureSupportSchemaReadyInstallStep::class);

    expect($step->key())->toBe('user-support.schema.ensure')
        ->and($step->package())->toBe('yezzmedia/laravel-user-support')
        ->and($step->priority())->toBe(20);
});

it('has correct key and priority on publish config step', function () {
    $step = app(PublishSupportConfigInstallStep::class);

    expect($step->key())->toBe('user-support.config.publish')
        ->and($step->package())->toBe('yezzmedia/laravel-user-support')
        ->and($step->priority())->toBe(10);
});
