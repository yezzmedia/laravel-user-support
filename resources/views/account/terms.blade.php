<div class="space-y-6">
    <x-account::page-header title="Allgemeine Geschäftsbedingungen" subtitle="Nutzungsbedingungen für die Plattform {{ config('app.name', 'Platform') }}" color="gray">
        <x-slot:icon>
            <x-account::icon name="file" class="h-5 w-5" />
        </x-slot:icon>
        <x-slot:badges>
            <x-account::badge class="bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                <x-account::icon name="file" class="mr-1 h-3 w-3" />
                AGB
            </x-account::badge>
        </x-slot:badges>
    </x-account::page-header>

    <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <div class="p-4 sm:p-6">
            <div class="prose prose-sm max-w-none dark:prose-invert">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">1. Geltungsbereich</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Diese Allgemeinen Geschäftsbedingungen (AGB) gelten für die Nutzung der Plattform {{ config('app.name', 'Platform') }}. Mit der Registrierung und Nutzung erkennen Sie diese Bedingungen an.
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">2. Vertragsschluss</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Mit der Registrierung kommt ein Nutzungsvertrag zwischen Ihnen und {{ config('app.name', 'Platform') }} zustande. Der Vertragsschluss erfolgt durch Ihre Anmeldung und die Bestätigung durch uns.
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">3. Pflichten des Nutzers</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Sie verpflichten sich, keine rechtswidrigen, beleidigenden oder gegen die guten Sitten verstoßenden Inhalte einzustellen. Die Plattform darf nicht missbräuchlich genutzt werden. Sie sind für die Sicherheit Ihres Zugangs selbst verantwortlich.
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">4. Haftung</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Wir haften unbeschränkt für Vorsatz und grobe Fahrlässigkeit sowie für die Verletzung von Leben, Körper und Gesundheit. Im Übrigen ist die Haftung auf das nach den jeweiligen gesetzlichen Bestimmungen übliche Maß beschränkt.
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">5. Kündigung</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Sie können Ihr Konto jederzeit über die Einstellungen löschen. Wir behalten uns vor, Ihren Zugang bei Verstoß gegen diese AGB zu sperren oder zu löschen.
                </p>

                <h3 class="text-base font-semibold text-gray-900 dark:text-white mt-4">6. Schlussbestimmungen</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Es gilt das Recht der Bundesrepublik Deutschland. Sollte eine Bestimmung dieser AGB unwirksam sein, bleibt die Wirksamkeit der übrigen Bestimmungen unberührt. Änderungen dieser AGB werden Ihnen rechtzeitig mitgeteilt.
                </p>
            </div>
        </div>
    </div>
</div>
