<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Database\Seeders;

use Illuminate\Database\Seeder;
use YezzMedia\UserSupport\Support\LegalContentManager;

final class LegalContentSeeder extends Seeder
{
    public function run(LegalContentManager $manager): void
    {
        $manager->upsert('impressum', 'de', 'Impressum', $this->impressumDe(), true);
        $manager->upsert('impressum', 'en', 'Impressum', $this->impressumEn(), true);
        $manager->upsert('privacy', 'de', 'Datenschutzerklärung', $this->privacyDe(), true);
        $manager->upsert('privacy', 'en', 'Privacy Policy', $this->privacyEn(), true);
        $manager->upsert('terms', 'de', 'Allgemeine Geschäftsbedingungen', $this->termsDe(), true);
        $manager->upsert('terms', 'en', 'Terms of Service', $this->termsEn(), true);
    }

    private function impressumDe(): string
    {
        return "## Angaben gemäß § 5 TMG\n\n"
            .config('app.name', 'Platform')."\n\n"
            ."Musterstraße 1\n"
            ."12345 Musterstadt\n\n"
            ."## Vertreten durch\n\n"
            ."Geschäftsführer: [Name]\n\n"
            ."## Kontakt\n\n"
            ."E-Mail: ".config('mail.from.address', 'info@example.com')."\n"
            ."Telefon: [Telefonnummer]\n\n"
            ."## Umsatzsteuer-ID\n\n"
            ."Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:\n"
            ."[DE-Nummer]\n\n"
            ."## Haftungsausschluss\n\n"
            ."Die Inhalte dieser Seiten wurden mit größter Sorgfalt erstellt. "
            ."Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte kann jedoch keine Gewähr übernommen werden.";
    }

    private function impressumEn(): string
    {
        return "## Information pursuant to § 5 TMG\n\n"
            .config('app.name', 'Platform')."\n\n"
            ."Sample Street 1\n"
            ."12345 Sample City\n\n"
            ."## Represented by\n\n"
            ."Managing Director: [Name]\n\n"
            ."## Contact\n\n"
            ."Email: ".config('mail.from.address', 'info@example.com')."\n"
            ."Phone: [Phone number]\n\n"
            ."## VAT ID\n\n"
            ."Sales tax identification number according to § 27 a VAT Act:\n"
            ."[VAT-ID]\n\n"
            ."## Disclaimer\n\n"
            ."The contents of these pages have been created with the utmost care. "
            ."However, we cannot guarantee the accuracy, completeness, or timeliness of the content.";
    }

    private function privacyDe(): string
    {
        return "Wir nehmen den Schutz Ihrer persönlichen Daten ernst. "
            ."Nachfolgend informieren wir Sie über die Verarbeitung Ihrer Daten im Sinne der "
            ."Datenschutz-Grundverordnung (DSGVO).\n\n"
            ."## 1. Verantwortliche Stelle\n\n"
            .config('app.name', 'Platform')."\n\n"
            ."E-Mail: ".config('mail.from.address', 'info@example.com')."\n\n"
            ."## 2. Erhobene Daten\n\n"
            ."Bei der Nutzung dieser Plattform werden folgende Daten verarbeitet:\n\n"
            ."- **Bestandsdaten:** Name, E-Mail-Adresse und Profilinformationen bei Registrierung\n"
            ."- **Nutzungsdaten:** IP-Adresse, Browsertyp, Betriebssystem, Zugriffszeiten\n"
            ."- **Kommunikationsdaten:** Nachrichteninhalte bei Support-Anfragen\n"
            ."- **Sicherheitsdaten:** Geräte- und Sitzungsinformationen für den Kontoschutz\n\n"
            ."## 3. Zweck der Verarbeitung\n\n"
            ."Die Datenverarbeitung erfolgt zur Bereitstellung der Plattform, zur Kontoverwaltung, "
            ."zur Bearbeitung von Support-Anfragen, zur Sicherheit und zur Erfüllung gesetzlicher Pflichten.\n\n"
            ."## 4. Rechtsgrundlage\n\n"
            ."Die Verarbeitung erfolgt auf Grundlage von Art. 6 Abs. 1 lit. b DSGVO (Vertragserfüllung), "
            ."lit. c (rechtliche Verpflichtung) und lit. f (berechtigtes Interesse).\n\n"
            ."## 5. Ihre Rechte\n\n"
            ."Sie haben das Recht auf Auskunft (Art. 15 DSGVO), Berichtigung (Art. 16), "
            ."Löschung (Art. 17), Einschränkung der Verarbeitung (Art. 18), "
            ."Datenübertragbarkeit (Art. 20) und Widerspruch (Art. 21). "
            ."Zur Ausübung Ihrer Rechte wenden Sie sich bitte an uns.\n\n"
            ."## 6. Speicherdauer\n\n"
            ."Wir speichern Ihre Daten nur so lange, wie es für die jeweiligen Verarbeitungszwecke "
            ."erforderlich ist oder gesetzliche Aufbewahrungspflichten bestehen.\n\n"
            ."## 7. Drittlandsübermittlung\n\n"
            ."Eine Übermittlung in Drittländer findet nur statt, wenn angemessene Garantien "
            ."gemäß Art. 44 ff. DSGVO bestehen.";
    }

    private function privacyEn(): string
    {
        return "We take the protection of your personal data very seriously. "
            ."Below we inform you about the processing of your data in accordance with "
            ."the General Data Protection Regulation (GDPR).\n\n"
            ."## 1. Controller\n\n"
            .config('app.name', 'Platform')."\n\n"
            ."Email: ".config('mail.from.address', 'info@example.com')."\n\n"
            ."## 2. Data Collected\n\n"
            ."When using this platform, the following data is processed:\n\n"
            ."- **Account data:** Name, email address, and profile information upon registration\n"
            ."- **Usage data:** IP address, browser type, operating system, access times\n"
            ."- **Communication data:** Message content for support inquiries\n"
            ."- **Security data:** Device and session information for account protection\n\n"
            ."## 3. Purpose of Processing\n\n"
            ."Data processing is carried out to provide the platform, manage accounts, "
            ."process support requests, ensure security, and fulfill legal obligations.\n\n"
            ."## 4. Legal Basis\n\n"
            ."Processing is based on Art. 6(1)(b) GDPR (contract performance), "
            ."(c) (legal obligation), and (f) (legitimate interest).\n\n"
            ."## 5. Your Rights\n\n"
            ."You have the right to access (Art. 15 GDPR), rectification (Art. 16), "
            ."erasure (Art. 17), restriction of processing (Art. 18), "
            ."data portability (Art. 20), and objection (Art. 21). "
            ."To exercise your rights, please contact us.\n\n"
            ."## 6. Storage Period\n\n"
            ."We only store your data for as long as necessary for the respective "
            ."processing purposes or as required by legal retention obligations.\n\n"
            ."## 7. Third-Country Transfers\n\n"
            ."Transfer to third countries only takes place if adequate safeguards "
            ."pursuant to Art. 44 et seq. GDPR exist.";
    }

    private function termsDe(): string
    {
        return "## 1. Geltungsbereich\n\n"
            ."Diese Allgemeinen Geschäftsbedingungen (AGB) gelten für die Nutzung der Plattform "
            .config('app.name', 'Platform').". "
            ."Mit der Registrierung und Nutzung erkennen Sie diese Bedingungen an.\n\n"
            ."## 2. Vertragsschluss\n\n"
            ."Mit der Registrierung kommt ein Nutzungsvertrag zwischen Ihnen und "
            .config('app.name', 'Platform')." zustande. "
            ."Der Vertragsschluss erfolgt durch Ihre Anmeldung und die Bestätigung durch uns.\n\n"
            ."## 3. Pflichten des Nutzers\n\n"
            ."Sie verpflichten sich, keine rechtswidrigen, beleidigenden oder gegen die guten Sitten "
            ."verstoßenden Inhalte einzustellen. Die Plattform darf nicht missbräuchlich genutzt werden. "
            ."Sie sind für die Sicherheit Ihres Zugangs selbst verantwortlich.\n\n"
            ."## 4. Haftung\n\n"
            ."Wir haften unbeschränkt für Vorsatz und grobe Fahrlässigkeit sowie für die Verletzung "
            ."von Leben, Körper und Gesundheit. Im Übrigen ist die Haftung auf das nach den "
            ."jeweiligen gesetzlichen Bestimmungen übliche Maß beschränkt.\n\n"
            ."## 5. Kündigung\n\n"
            ."Sie können Ihr Konto jederzeit über die Einstellungen löschen. Wir behalten uns vor, "
            ."Ihren Zugang bei Verstoß gegen diese AGB zu sperren oder zu löschen.\n\n"
            ."## 6. Schlussbestimmungen\n\n"
            ."Es gilt das Recht der Bundesrepublik Deutschland. Sollte eine Bestimmung dieser AGB "
            ."unwirksam sein, bleibt die Wirksamkeit der übrigen Bestimmungen unberührt. "
            ."Änderungen dieser AGB werden Ihnen rechtzeitig mitgeteilt.";
    }

    private function termsEn(): string
    {
        return "## 1. Scope\n\n"
            ."These Terms of Service apply to the use of the platform "
            .config('app.name', 'Platform').". "
            ."By registering and using the platform, you accept these terms.\n\n"
            ."## 2. Contract Conclusion\n\n"
            ."By registering, a usage contract is concluded between you and "
            .config('app.name', 'Platform').". "
            ."The contract is concluded through your registration and our confirmation.\n\n"
            ."## 3. User Obligations\n\n"
            ."You agree not to post any unlawful, offensive, or immoral content. "
            ."The platform must not be used abusively. You are responsible for the security of your account.\n\n"
            ."## 4. Liability\n\n"
            ."We assume unlimited liability for intent and gross negligence as well as for "
            ."injury to life, body, and health. Otherwise, liability is limited to the "
            ."usual extent under the respective legal provisions.\n\n"
            ."## 5. Termination\n\n"
            ."You can delete your account at any time via the settings. We reserve the right "
            ."to block or delete your access in case of violation of these terms.\n\n"
            ."## 6. Final Provisions\n\n"
            ."The law of the Federal Republic of Germany applies. Should any provision of these "
            ."terms be invalid, the validity of the remaining provisions shall remain unaffected. "
            ."Changes to these terms will be communicated to you in a timely manner.";
    }
}
