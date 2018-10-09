<?php
declare(strict_types=1);

return [
    'connection'  => 'default',
    'connections' => [
        'default' => [
            'url' => getenv('DATABASE_URL'),
        ],
    ],
];