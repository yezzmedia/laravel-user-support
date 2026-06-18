<div class="space-y-6">
    <x-account::page-header title="Datenschutzerklärung" subtitle="Informationen zur Verarbeitung personenbezogener Daten" color="gray">
        <x-slot:icon>
            <x-account::icon name="shield" class="h-5 w-5" />
        </x-slot:icon>
        <x-slot:badges>
            <x-account::badge class="bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                <x-account::icon name="shield" class="mr-1 h-3 w-3" />
                DSGVO
            </x-account::badge>
        </x-slot:badges>
    </x-account::page-header>

    <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <div class="p-4 sm:p-6">
            <div class="prose prose-sm max-w-none dark:prose-invert">
                <p class="text-sm text-gray-500 dark:text-gray-400">Wir nehmen den Schutz Ihrer persönlichen Daten ernst. Nachfolgend informieren wir Sie über die Verarbeitung Ihrer Daten im Sinne der Datenschutz-Grundverordnung (DSGVO).</p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">1. Verantwortliche Stelle</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ config('app.name', 'Platform') }}<br>
                    Musterstraße 1, 12345 Musterstadt<br>
                    E-Mail: {{ config('mail.from.address', 'info@example.com') }}
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">2. Erhobene Daten</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Bei der Nutzung dieser Plattform werden folgende Daten verarbeitet:
                </p>
                <ul class="text-sm text-gray-500 dark:text-gray-400 list-disc pl-5 space-y-1">
                    <li><strong>Bestandsdaten:</strong> Name, E-Mail-Adresse und Profilinformationen bei Registrierung</li>
                    <li><strong>Nutzungsdaten:</strong> IP-Adresse, Browsertyp, Betriebssystem, Zugriffszeiten</li>
                    <li><strong>Kommunikationsdaten:</strong> Nachrichteninhalte bei Support-Anfragen</li>
                    <li><strong>Sicherheitsdaten:</strong> Geräte- und Sitzungsinformationen für den Kontoschutz</li>
                </ul>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">3. Zweck der Verarbeitung</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Die Datenverarbeitung erfolgt zur Bereitstellung der Plattform, zur Kontoverwaltung, zur Bearbeitung von Support-Anfragen, zur Sicherheit und zur Erfüllung gesetzlicher Pflichten.
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">4. Rechtsgrundlage</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Die Verarbeitung erfolgt auf Grundlage von Art. 6 Abs. 1 lit. b DSGVO (Vertragserfüllung), lit. c (rechtliche Verpflichtung) und lit. f (berechtigtes Interesse).
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">5. Ihre Rechte</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Sie haben das Recht auf Auskunft (Art. 15 DSGVO), Berichtigung (Art. 16), Löschung (Art. 17), Einschränkung der Verarbeitung (Art. 18), Datenübertragbarkeit (Art. 20) und Widerspruch (Art. 21). Zur Ausübung Ihrer Rechte wenden Sie sich bitte an uns.
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">6. Speicherdauer</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Wir speichern Ihre Daten nur so lange, wie es für die jeweiligen Verarbeitungszwecke erforderlich ist oder gesetzliche Aufbewahrungspflichten bestehen.
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">7. Drittlandsübermittlung</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Eine Übermittlung in Drittländer findet nur statt, wenn angemessene Garantien gemäß Art. 44 ff. DSGVO bestehen.
                </p>
            </div>
        </div>
    </div>
</div>
