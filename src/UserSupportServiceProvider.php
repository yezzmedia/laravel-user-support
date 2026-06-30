<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use YezzMedia\Dashboard\Navigation\BottomBarLink;
use YezzMedia\Dashboard\Navigation\BottomBarLinkRegistry;
use YezzMedia\Foundation\Support\PlatformPackageRegistrar;
use YezzMedia\UserSupport\Support\LegalContentManager;
use YezzMedia\UserSupport\Support\SupportTicketManager;

class UserSupportServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-user-support')
            ->hasConfigFile('user-support')
            ->hasViews()
            ->hasTranslations()
            ->hasMigration('0001_create_support_tickets_table')
            ->hasMigration('0002_create_support_ticket_replies_table')
            ->hasMigration('0003_create_legal_contents_table')
            ->runsMigrations();
    }

    public function registeringPackage(): void
    {
        $config = $this->app->make('config');
        $existing = $config->get('user-support');
        if (! is_array($existing)) {
            $config->set('user-support', []);
        }
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(SupportTicketManager::class);
        $this->app->singleton(LegalContentManager::class);
    }

    public function packageBooted(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'user-support');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'user-support');

        $this->app->make(PlatformPackageRegistrar::class)
            ->register(new UserSupportPlatformPackage);

        $this->injectBottomBarLinks();
    }

    private function injectBottomBarLinks(): void
    {
        if (! $this->app->bound(BottomBarLinkRegistry::class)) {
            return;
        }

        $registry = $this->app->make(BottomBarLinkRegistry::class);

        $registry->add(new BottomBarLink(
            label: __('user-support::user-support.bottom_bar_help'),
            url: url('/account/support'),
            section: 'left',
            sort: 10,
        ));

        $registry->add(new BottomBarLink(
            label: __('user-support::user-support.bottom_bar_impressum'),
            url: url('/account/legal-impressum'),
            section: 'right',
            sort: 30,
        ));

        $registry->add(new BottomBarLink(
            label: __('user-support::user-support.bottom_bar_privacy'),
            url: url('/account/legal-privacy'),
            section: 'right',
            sort: 40,
        ));

        $registry->add(new BottomBarLink(
            label: __('user-support::user-support.bottom_bar_terms'),
            url: url('/account/legal-terms'),
            section: 'right',
            sort: 50,
        ));
    }
}
