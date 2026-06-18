# yezzmedia/laravel-user-support — Plan

## Item 5: BottomBarLink-Timing ✅ (completed)

### Problem

`UserSupportServiceProvider::packageBooted()` injiziert 4 BottomBarLinks in `BottomBarLinkRegistry`.
Damit das funktioniert, muss `yezzmedia/laravel-dashboard` installiert und dessen `BottomBarLinkRegistry`
vor UserSupport's `packageBooted()` als Singleton gebunden sein.

### Timing (korrekt)

| Phase | Dashboard | UserSupport |
|-------|-----------|-------------|
| `register()` | `packageRegistered()` bindet `BottomBarLinkRegistry` als Singleton | `packageRegistered()` bindet `SupportTicketManager` |
| `boot()` | `packageBooted()` seedt Default-Links + registriert `$app->booted()`-Callback für sealing | `packageBooted()` ruft `injectBottomBarLinks()` auf → 4 Links werden hinzugefügt |
| `$app->booted()` | Registry wird versiegelt (`seal()`) | — |

### Voraussetzung

- Dashboard muss **vor** UserSupport in der Service-Provider-Liste stehen, damit
  `BottomBarLinkRegistry::class` während UserSupport's `registeringPackage()` bereits existiert.
- Die Guard `$this->app->bound(BottomBarLinkRegistry::class)` verhindert Abstürze, falls
  Dashboard nicht installiert ist (Graceful Degradation).

### Tests (3 Tests, 18 Assertions)

- `BottomBarLinkInjectionTest` (in `tests/Feature/`)
  - `injects_four_bottom_bar_links` — prüft Anzahl, Labels und URLs der 4 Links
  - `injects_help_link_in_left_section` — Help & Support ist im `left`-Bereich
  - `injects_legal_links_in_right_section` — Impressum, Datenschutz, AGB sind im `right`-Bereich

---

## Item 6: Guest-Support (offen)

### Ziel

Gäste (nicht eingeloggte Benutzer) sollen ein Kontaktformular absenden können, das ein
Support-Ticket erstellt. Dafür sind nötig:

- Guest-Token-Mechanismus (z. B. UUID per Session oder Cookie)
- `sendContact`-Methode in `SupportPage` muss ohne `auth()->user()` funktionieren
- E-Mail-Benachrichtigung an admin bei neuer Gast-Anfrage
- Optional: E-Mail-Bestätigung an Gast
- Neue Translations-Schlüssel
