<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use YezzMedia\Dashboard\DashboardServiceProvider;
use YezzMedia\Dashboard\Navigation\BottomBarLinkRegistry;
use YezzMedia\UserSupport\Tests\UserSupportTestCase;

final class BottomBarLinkInjectionTest extends UserSupportTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            DashboardServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('dashboard.legal', [
            'left' => [],
            'right' => [],
        ]);
    }

    #[Test]
    public function injects_four_bottom_bar_links(): void
    {
        $registry = $this->app->make(BottomBarLinkRegistry::class);
        $config = $registry->toConfigArray();

        $this->assertArrayHasKey('left', $config);
        $this->assertArrayHasKey('right', $config);
        $this->assertCount(1, $config['left']);
        $this->assertCount(3, $config['right']);

        $this->assertSame('Help & Support', $config['left'][0]['label']);
        $this->assertStringContainsString('/account/support', $config['left'][0]['url']);

        $this->assertSame('Impressum', $config['right'][0]['label']);
        $this->assertStringContainsString('/account/legal-impressum', $config['right'][0]['url']);

        $this->assertSame('Datenschutz', $config['right'][1]['label']);
        $this->assertStringContainsString('/account/legal-privacy', $config['right'][1]['url']);

        $this->assertSame('AGB', $config['right'][2]['label']);
        $this->assertStringContainsString('/account/legal-terms', $config['right'][2]['url']);
    }

    #[Test]
    public function injects_help_link_in_left_section(): void
    {
        $registry = $this->app->make(BottomBarLinkRegistry::class);
        $config = $registry->toConfigArray();

        $this->assertCount(1, $config['left']);
        $this->assertSame('Help & Support', $config['left'][0]['label']);
    }

    #[Test]
    public function injects_legal_links_in_right_section(): void
    {
        $registry = $this->app->make(BottomBarLinkRegistry::class);
        $config = $registry->toConfigArray();

        $this->assertCount(3, $config['right']);

        $labels = array_column($config['right'], 'label');
        $this->assertContains('Impressum', $labels);
        $this->assertContains('Datenschutz', $labels);
        $this->assertContains('AGB', $labels);
    }
}
