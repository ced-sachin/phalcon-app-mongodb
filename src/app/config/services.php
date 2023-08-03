<?php

use Phalcon\Di\FactoryDefault;
use MongoDB\Client;

$di = new FactoryDefault();

// Other services...

$di->set('mongo', function () {
    $config = $this->getConfig()->database;
    $uri = 'mongodb://' . $config->host . ':' . $config->port;
    if (!empty($config->username) && !empty($config->password)) {
        $uri = $config->username . ':' . $config->password . '@' . $uri;
    }
    $uri .= '/' . $config->dbname;
    return new Client($uri, $config->options->toArray());
});