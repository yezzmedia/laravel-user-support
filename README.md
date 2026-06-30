<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/yezzmedia/.github/main/profile/yezzmedia-dark.svg">
    <img src="https://raw.githubusercontent.com/yezzmedia/.github/main/profile/yezzmedia-light.svg" alt="Yezz Media" height="40">
  </picture>
</p>

<p align="center">
  <a href="https://packagist.org/packages/yezzmedia/laravel-user-support"><img src="https://img.shields.io/packagist/v/yezzmedia/laravel-user-support?style=flat-square" alt="Latest Version"></a>
  <a href="https://packagist.org/packages/yezzmedia/laravel-user-support"><img src="https://img.shields.io/packagist/php-v/yezzmedia/laravel-user-support?style=flat-square" alt="PHP Version"></a>
  <a href="https://packagist.org/packages/yezzmedia/laravel-user-support"><img src="https://img.shields.io/packagist/l/yezzmedia/laravel-user-support?style=flat-square" alt="License"></a>
</p>

---

# Laravel User &middot; Support

`yezzmedia/laravel-user-support` provides a user-facing support ticket system, public contact form, and legal content pages for the Yezz Media platform.

It integrates with the dashboard bottom-bar and the account sidebar for seamless user access.

## Version

Current release: `0.2.0`

## Requirements

- PHP `^8.5`
- Laravel `^13.0` components
- `spatie/laravel-package-tools ^1.93`
- `yezzmedia/laravel-foundation ^0.2`
- `yezzmedia/laravel-account ^0.2`
- `yezzmedia/laravel-dashboard ^0.2`

## Installation

```bash
composer require yezzmedia/laravel-user-support
```

## What The Package Provides

### Support Tickets

A user-facing ticket system with:

- Ticket creation with subject, category, and message
- Reply threading with operator and user messages
- Ticket status tracking (open, in-progress, resolved, closed)
- Per-ticket activity timeline

### Public Contact Form

A standalone contact form accessible without authentication, integrated through the dashboard bottom-bar.

### Legal Pages

Static legal content pages:

- **Impressum** — legal notice with company details
- **Privacy Policy** — data protection and privacy information
- **Terms of Service** — platform usage terms

### Bottom-Bar Integration

Injects bottom-bar links for help center and legal pages through `BottomBarLinkRegistry`.

### Account Integration

Registers navigation items in the account sidebar for support ticket access.

## Development

```bash
composer test
composer analyse
composer format
```

## License

MIT
