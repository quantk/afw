<?php
declare(strict_types=1);

use Afw\Component\Util\Env;

return [
    'connection'  => 'default',
    'connections' => [
        'default' => [
            'url' => Env::get('DATABASE_URL'),
        ],
    ],
];