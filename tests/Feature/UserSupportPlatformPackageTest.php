<?php

declare(strict_types=1);

use YezzMedia\UserSupport\UserSupportPlatformPackage;

it('provides correct package metadata', function () {
    $pkg = app(UserSupportPlatformPackage::class);

    $meta = $pkg->metadata();

    expect($meta->name)->toBe('yezzmedia/laravel-user-support')
        ->and($meta->vendor)->toBe('yezzmedia')
        ->and($meta->description)->toBeString();
});

it('defines support.manage permission', function () {
    $pkg = app(UserSupportPlatformPackage::class);

    $perms = $pkg->permissionDefinitions();

    expect($perms)->toHaveCount(1)
        ->and($perms[0]->name)->toBe('support.manage')
        ->and($perms[0]->package)->toBe('yezzmedia/laravel-user-support');
});

it('defines expected features', function () {
    $pkg = app(UserSupportPlatformPackage::class);

    $features = $pkg->featureDefinitions();

    expect($features)->toHaveCount(4);

    $keys = array_map(fn ($f) => $f->name, $features);
    expect($keys)->toContain('support.tickets')
        ->toContain('support.legal.impressum')
        ->toContain('support.legal.privacy')
        ->toContain('support.legal.terms');
});

it('defines audit events', function () {
    $pkg = app(UserSupportPlatformPackage::class);

    $events = $pkg->auditEventDefinitions();

    expect($events)->toHaveCount(3);

    $keys = array_map(fn ($e) => $e->key, $events);
    expect($keys)->toContain('support.ticket.created')
        ->toContain('support.ticket.replied')
        ->toContain('support.ticket.closed');
});

it('defines ops modules for tickets and legal', function () {
    $pkg = app(UserSupportPlatformPackage::class);

    $modules = $pkg->opsModuleDefinitions();

    expect($modules)->toHaveCount(2);

    $keys = array_map(fn ($m) => $m->key, $modules);
    expect($keys)->toContain('support.tickets')
        ->toContain('support.legal');
});

it('provides install steps', function () {
    $pkg = app(UserSupportPlatformPackage::class);

    $steps = $pkg->installSteps();

    expect($steps)->toHaveCount(4)
        ->and($steps[0]->key())->toBe('user-support.config.publish')
        ->and($steps[1]->key())->toBe('user-support.schema.ensure');
});

it('provides doctor checks', function () {
    $pkg = app(UserSupportPlatformPackage::class);

    $checks = $pkg->doctorChecks();

    expect($checks)->toHaveCount(4)
        ->and($checks[0]->key())->toBe('user-support.config.published')
        ->and($checks[1]->key())->toBe('user-support.schema.ready');
});
