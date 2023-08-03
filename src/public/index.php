<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use MongoDB\Client;

$config = new Config([]);

require_once __DIR__.'/../vendor/autoload.php';
// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$application = new Application($container);



// $container->set(
//     'db',
//     function () {
//         return new Mysql(
//             [
//                 'host'     => 'phalcon-app-mysql-server-1',
//                 'username' => 'root',
//                 'password' => 'secret',
//                 'dbname'   => 'phalt',
//                 ]
//             );
//         }
// );


$container->set(
    'mongo',
    function () {
        $mongo = new MongoClient();

        return $mongo->selectDB('phalconmongo');
    },
    true
);

// Connecting to a domain socket, falling back to localhost connection
$container->set(
    'mongo',
    function () {
        $mongo = new MongoClient(
            'mongo:///tmp/mongodb-27017.sock,localhost:27017'
        );

        return $mongo->selectDB('container');
    },
    true
);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
