<?php

declare(strict_types=1);

use YezzMedia\UserSupport\Models\LegalContent;
use YezzMedia\UserSupport\Support\LegalContentManager;

beforeEach(function (): void {
    LegalContent::create([
        'key' => 'impressum',
        'locale' => 'de',
        'title' => 'Impressum',
        'content' => '## Test Impressum',
        'published' => true,
    ]);

    LegalContent::create([
        'key' => 'privacy',
        'locale' => 'de',
        'title' => 'Datenschutz',
        'content' => 'Datenschutzinhalt',
        'published' => true,
    ]);
});

it('can create legal content', function (): void {
    $content = LegalContent::where('key', 'impressum')->first();

    expect($content)->not->toBeNull()
        ->and($content->title)->toBe('Impressum')
        ->and($content->locale)->toBe('de');
});

it('manager returns content', function (): void {
    $manager = app(LegalContentManager::class);

    $content = $manager->content('impressum');

    expect($content)->not->toBeNull()
        ->and($content)->toContain('Test Impressum');
});

it('manager returns null for missing key', function (): void {
    $manager = app(LegalContentManager::class);

    $content = $manager->content('nonexistent');

    expect($content)->toBeNull();
});

it('manager renders markdown', function (): void {
    $manager = app(LegalContentManager::class);

    $html = $manager->render('## Heading');

    expect($html)->toContain('<h2>Heading</h2>');
});

it('manager returns title', function (): void {
    $manager = app(LegalContentManager::class);

    $title = $manager->title('privacy');

    expect($title)->toBe('Datenschutz');
});

it('manager upserts content', function (): void {
    $manager = app(LegalContentManager::class);

    $result = $manager->upsert('impressum', 'de', 'Updated', '## Updated Content');

    expect($result->title)->toBe('Updated')
        ->and($result->content)->toBe('## Updated Content');
});

it('manager creates new content via upsert', function (): void {
    $manager = app(LegalContentManager::class);

    $result = $manager->upsert('terms', 'de', 'AGB', '## AGB Inhalt');

    expect($result->key)->toBe('terms')
        ->and($result->title)->toBe('AGB');
});

it('manager hasContent returns correct', function (): void {
    $manager = app(LegalContentManager::class);

    expect($manager->hasContent('impressum'))->toBeTrue()
        ->and($manager->hasContent('nonexistent'))->toBeFalse();
});

it('manager allPublished returns published items', function (): void {
    $manager = app(LegalContentManager::class);

    $all = $manager->allPublished();

    expect($all)->toHaveCount(2);
});

it('manager only returns published content', function (): void {
    LegalContent::create([
        'key' => 'terms',
        'locale' => 'de',
        'title' => 'AGB',
        'content' => 'AGB Inhalt',
        'published' => false,
    ]);

    $manager = app(LegalContentManager::class);

    expect($manager->hasContent('terms'))->toBeFalse();
});

it('manager falls back to de locale', function (): void {
    $manager = app(LegalContentManager::class);

    $manager->setLocale('en');
    $content = $manager->content('impressum');

    expect($content)->not->toBeNull()
        ->and($content)->toContain('Test Impressum');
});
