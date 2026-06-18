<div class="space-y-6">
    <x-account::page-header title="Impressum" subtitle="Rechtliche Angaben gemäß § 5 TMG" color="gray">
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
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Angaben gemäß § 5 TMG</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ config('app.name', 'Platform') }}<br>
                    Musterstraße 1<br>
                    12345 Musterstadt
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">Vertreten durch</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Geschäftsführer: [Name]<br>
                    Registergericht: [Amtsgericht]<br>
                    Registernummer: [HRB-Nummer]
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">Kontakt</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    E-Mail: {{ config('mail.from.address', 'info@example.com') }}<br>
                    Telefon: [Telefonnummer]
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">Umsatzsteuer-ID</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:<br>
                    [DE-Nummer]
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">Verantwortlich für Inhalte</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    [Name], [Adresse] – gemäß § 55 Abs. 2 RStV
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">Haftungsausschluss</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Die Inhalte dieser Seiten wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte kann jedoch keine Gewähr übernommen werden.
                </p>
            </div>
        </div>
    </div>
</div>
