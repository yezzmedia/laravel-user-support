<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Support;

use Illuminate\Support\Str;
use YezzMedia\UserSupport\Models\LegalContent;

final class LegalContentManager
{
    private ?string $cachedLocale = null;

    /** @var array<string, LegalContent> */
    private array $cache = [];

    public function setLocale(string $locale): void
    {
        $this->cachedLocale = $locale;
        $this->cache = [];
    }

    public function content(string $key, ?string $locale = null): ?string
    {
        $record = $this->get($key, $locale);

        if ($record === null) {
            return null;
        }

        return $this->render($record->content);
    }

    public function title(string $key, ?string $locale = null): ?string
    {
        $record = $this->get($key, $locale);

        return $record?->title;
    }

    public function get(string $key, ?string $locale = null): ?LegalContent
    {
        $locale ??= $this->cachedLocale ?? app()->getLocale();

        $cacheKey = "{$key}:{$locale}";

        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $record = LegalContent::where('key', $key)
            ->where('locale', $locale)
            ->where('published', true)
            ->first();

        if ($record === null) {
            $record = LegalContent::where('key', $key)
                ->where('locale', 'de')
                ->where('published', true)
                ->first();
        }

        $this->cache[$cacheKey] = $record;

        return $record;
    }

    public function render(string $markdown): string
    {
        return Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    public function upsert(string $key, string $locale, string $title, string $content, bool $published = true, ?int $updatedBy = null): LegalContent
    {
        $this->cache = [];

        return LegalContent::updateOrCreate(
            ['key' => $key, 'locale' => $locale],
            ['title' => $title, 'content' => $content, 'published' => $published, 'updated_by' => $updatedBy],
        );
    }

    public function hasContent(string $key, ?string $locale = null): bool
    {
        return $this->get($key, $locale) !== null;
    }

    public function allPublished(): array
    {
        return LegalContent::where('published', true)
            ->orderBy('key')
            ->orderBy('locale')
            ->get()
            ->all();
    }
}
