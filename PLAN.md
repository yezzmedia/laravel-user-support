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

### Architectural Decision: Kein Interface-Extract

Nach Analyse der tatsächlichen Dependencies und Consumer:

**Abhängigkeitsgraph (Stand heute):**
```
UserSupport  ──require──> Dashboard (BottomBarLinkRegistry, hard dep)
UserSupport  ──require──> Account   (AccountPage, hard dep)
Account      ──class_exists──> Dashboard (fakultativ: @includeIf + conditional menu-item)
Account      ──require──> Foundation
UserConsent  ──suggest──> Dashboard (BottomBarLink via config, optional)
UserConsent  ──suggest──> Account   (Sidebarlink via config, optional)
```

### Dependency-Audit (18.06.2026)

| Package | require (yezzmedia) | Fehlt? |
|---------|--------------------|--------|
| `laravel-foundation` | — | ✅ sauber |
| `laravel-dashboard` | foundation | ✅ sauber |
| `laravel-account` | foundation | ⚠️ Dashboard nur suggest, obwohl `class_exists` + `@includeIf` verwendet |
| `laravel-user-support` | **account**, dashboard, foundation | ✅ **Fix: account war fälschlich nicht in require — ergänzt** |
| `laravel-user-consent` | foundation | ⚠️ Dashboard + Account nur suggest, obwohl BottomBarLink + SidebarLink injiziert (via Config-Manipulation) |

**Empfehlung:** user-consent sollte auf Registry-Pattern umgestellt werden und dafür
`laravel-dashboard` sowie `laravel-account` als require bekommen.

### Architectural Decision: Kein Interface-Extract

Nach Analyse der tatsächlichen Dependencies und Consumer:
- Account hat kein Hard-Dependency auf Dashboard → wer BottomBarLinks injecten will, muss Dashboard
  explizit requiren
- UserSupport hat `laravel-dashboard: ^0.1` bereits als Hard-Dependency → das Registry ist garantiert
  verfügbar
- `class_exists()`-Guards auf allen Ebenen verhindern Crashs bei fehlendem Dashboard
- Erst bei 4+ Consumern lohnt sich ein formales Interface

### Abweichendes Pattern in laravel-user-consent — Broken

`laravel-user-consent` manipuliert `config('dashboard.legal')` direkt (nicht `BottomBarLinkRegistry::add()`)
und registriert den Callback in `$app->booted()` (nicht `packageBooted()`).

**Problem:** Dashboard liest `config('dashboard.legal')` ebenfalls in `$app->booted()` und
versiegelt danach das Registry. Da Dashboard seinen Callback vor user-consent registriert, läuft
Dashboard's Callback zuerst → Config wird gelesen und Registry versiegelt. User-consent modifiziert
die Config danach, aber Dashboard hat sie bereits konsumiert. **Der BottomBarLink von user-consent
wird also nie gerendert.**

**Fix:** user-consent müsste auf `BottomBarLinkRegistry::add()` in `packageBooted()` umgestellt werden
(wie UserSupport), und `laravel-dashboard` von `suggest` auf `require` wechseln.

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
