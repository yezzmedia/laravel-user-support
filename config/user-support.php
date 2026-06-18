<?php

declare(strict_types=1);

return [

    'tickets' => [
        'enabled' => true,
        'ticket_number_start' => 10000000,
        'notification_email' => null,
        'allow_guests' => true,
        'notify_guest' => false,
    ],

    'legal' => [
        'legal-impressum' => ['enabled' => true, 'route' => '/rechtliches/impressum'],
        'legal-privacy' => ['enabled' => true, 'route' => '/rechtliches/datenschutz'],
        'legal-terms' => ['enabled' => true, 'route' => '/rechtliches/agb'],
    ],

];
