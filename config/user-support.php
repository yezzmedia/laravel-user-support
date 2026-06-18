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
        'impressum' => ['enabled' => true, 'route' => '/impressum'],
        'privacy' => ['enabled' => true, 'route' => '/datenschutz'],
        'terms' => ['enabled' => true, 'route' => '/agb'],
    ],

];
