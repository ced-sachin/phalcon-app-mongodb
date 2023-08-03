<?php

use Phalcon\Config;

return new Config([
    // Other configurations
    'database' => [
        'adapter' => 'mongodb',
        'host' => 'localhost',
        'port' => 27017,
        'username' => 'root', // If authentication is enabled
        'password' => 'password123', // If authentication is enabled
        'dbname' => 'phalconmongo',
        'options' => [
            // Additional MongoDB options if needed
        ],
    ],
]);