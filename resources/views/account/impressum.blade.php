<div class="space-y-6">
    <x-account::page-header :title="$title" subtitle="Rechtliche Angaben gemäß § 5 TMG" color="gray">
        <x-slot:icon>
            <x-account::icon name="file" class="h-5 w-5" />
        </x-slot:icon>
        <x-slot:badges>
            <x-account::badge class="bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                <x-account::icon name="file" class="mr-1 h-3 w-3" />
                {{ config('app.name', 'Platform') }}
            </x-account::badge>
        </x-slot:badges>
    </x-account::page-header>

    <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <div class="p-4 sm:p-6">
            <div class="prose prose-sm max-w-none dark:prose-invert">
                @if ($content)
                    {!! $content !!}
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">Noch kein Impressum hinterlegt.</p>
                @endif
            </div>
        </div>
    </div>
</div>
