<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class LegalContent extends Model
{
    protected $fillable = [
        'key',
        'locale',
        'title',
        'content',
        'published',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
        ];
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', 'App\\Models\\User'), 'updated_by');
    }
}
