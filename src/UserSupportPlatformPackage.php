<?php

declare(strict_types=1);

namespace YezzMedia\UserSupport;

use YezzMedia\Foundation\Contracts\DefinesAuditEvents;
use YezzMedia\Foundation\Contracts\DefinesInstallSteps;
use YezzMedia\Foundation\Contracts\DefinesPermissions;
use YezzMedia\Foundation\Contracts\PlatformPackage;
use YezzMedia\Foundation\Contracts\ProvidesDoctorChecks;
use YezzMedia\Foundation\Contracts\ProvidesOpsModules;
use YezzMedia\Foundation\Contracts\RegistersFeatures;
use YezzMedia\Foundation\Data\AuditEventDefinition;
use YezzMedia\Foundation\Data\FeatureDefinition;
use YezzMedia\Foundation\Data\OpsModuleDefinition;
use YezzMedia\Foundation\Data\PackageMetadata;
use YezzMedia\Foundation\Data\PermissionDefinition;
use YezzMedia\Foundation\Doctor\DoctorCheck;
use YezzMedia\Foundation\Install\InstallStep;
use YezzMedia\UserSupport\Doctor\LegalContentSeedCheck;
use YezzMedia\UserSupport\Doctor\LegalSchemaReadyCheck;
use YezzMedia\UserSupport\Doctor\SupportConfigPublishedCheck;
use YezzMedia\UserSupport\Doctor\SupportSchemaReadyCheck;
use YezzMedia\UserSupport\Install\EnsureLegalSchemaReadyInstallStep;
use YezzMedia\UserSupport\Install\EnsureSupportSchemaReadyInstallStep;
use YezzMedia\UserSupport\Install\PublishSupportConfigInstallStep;
use YezzMedia\UserSupport\Install\SeedLegalContentInstallStep;

final class UserSupportPlatformPackage implements DefinesAuditEvents, DefinesInstallSteps, DefinesPermissions, PlatformPackage, ProvidesDoctorChecks, ProvidesOpsModules, RegistersFeatures
{
    public function metadata(): PackageMetadata
    {
        return new PackageMetadata(
            name: 'yezzmedia/laravel-user-support',
            vendor: 'yezzmedia',
            description: 'User-facing support ticket system with public contact form and legal pages.',
            packageClass: self::class,
        );
    }

    public function permissionDefinitions(): array
    {
        return [
            new PermissionDefinition(
                name: 'support.manage',
                package: 'yezzmedia/laravel-user-support',
                label: 'Manage support tickets',
                description: 'Allows operator-facing management of support tickets and replies.',
            ),
        ];
    }

    public function featureDefinitions(): array
    {
        return [
            new FeatureDefinition('support.tickets', 'yezzmedia/laravel-user-support', 'Support tickets', 'Provides the ticket-based conversation system between users and operators.'),
            new FeatureDefinition('support.legal.impressum', 'yezzmedia/laravel-user-support', 'Impressum page', 'Legal notice page for German law compliance.'),
            new FeatureDefinition('support.legal.privacy', 'yezzmedia/laravel-user-support', 'Privacy policy page', 'Data protection information for users.'),
            new FeatureDefinition('support.legal.terms', 'yezzmedia/laravel-user-support', 'Terms of service page', 'Platform terms and conditions.'),
        ];
    }

    public function auditEventDefinitions(): array
    {
        return [
            new AuditEventDefinition('support.ticket.created', 'yezzmedia/laravel-user-support', 'created', 'support_ticket', 'A support ticket was created.', 'info', ['user_id', 'subject', 'source']),
            new AuditEventDefinition('support.ticket.replied', 'yezzmedia/laravel-user-support', 'replied', 'support_ticket', 'A reply was added to a support ticket.', 'info', ['user_id', 'ticket_id', 'sender_type', 'source']),
            new AuditEventDefinition('support.ticket.closed', 'yezzmedia/laravel-user-support', 'closed', 'support_ticket', 'A support ticket was closed.', 'info', ['user_id', 'ticket_id', 'source']),
        ];
    }

    public function opsModuleDefinitions(): array
    {
        return [
            new OpsModuleDefinition(
                key: 'support.tickets',
                package: 'yezzmedia/laravel-user-support',
                label: 'Support Tickets',
                type: 'page',
                permissionHint: 'support.manage',
            ),
            new OpsModuleDefinition(
                key: 'support.legal',
                package: 'yezzmedia/laravel-user-support',
                label: 'Legal Content',
                type: 'page',
                permissionHint: 'support.manage',
            ),
        ];
    }

    /**
     * @return array<int, InstallStep>
     */
    public function installSteps(): array
    {
        return [
            app(PublishSupportConfigInstallStep::class),
            app(EnsureSupportSchemaReadyInstallStep::class),
            app(EnsureLegalSchemaReadyInstallStep::class),
            app(SeedLegalContentInstallStep::class),
        ];
    }

    /**
     * @return array<int, DoctorCheck>
     */
    public function doctorChecks(): array
    {
        return [
            app(SupportConfigPublishedCheck::class),
            app(SupportSchemaReadyCheck::class),
            app(LegalSchemaReadyCheck::class),
            app(LegalContentSeedCheck::class),
        ];
    }
}
