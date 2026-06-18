<x-filament-panels::page>
    <div style="border:1px solid #e5e7eb; background:#fff; border-radius:0.25rem; padding:1rem;">
        {{ $this->form }}
    </div>

    @if ($this->record->content ?? false)
        <div style="border:1px solid #e5e7eb; background:#fff; border-radius:0.25rem; padding:1rem; margin-top:1rem;">
            <div style="font-weight:600; color:#6b7280; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">Preview</div>
            <div class="prose prose-sm max-w-none dark:prose-invert">
                {!! app(\YezzMedia\UserSupport\Support\LegalContentManager::class)->render($this->record->content) !!}
            </div>
        </div>
    @endif
</x-filament-panels::page>
