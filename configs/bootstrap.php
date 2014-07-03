<?php

include_once(realpath(__DIR__ . '/../') . '/vendor/autoload.php');

$di = new \Application\Di();

/**
 * Configs
 */
$di->set('configs', function () use ($environment) {
    return new \Phalcon\Config\Adapter\Php(__DIR__ . '/env_' . $environment . '.php');
}, true);

/**
 * Router
 */
$di->set('router', function () {
    $router = new Phalcon\Mvc\Router();
    $router->clear(); // Öntanımlı bazı route patternları temizler.
    $router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI); // URI otomatik olarak çözümlenir.
    $router->notFound(['module' => 'rest', 'controller' => 'not-found', 'action' => 'handle']);
    $router->add('/api', 'rest::welcome::handle');
    return $router;
}, true);

/**
 * Dispatcher
 */
$di->set('dispatcher', function () use ($di) {
    return new \Application\Web\Dispatcher($di);
}, true);

/**
 * Logger Helper
 */
$di->set('logger_helper', function () use ($di) {
    return new \Application\Logger\Helper($di);
}, true);

/**
 * PDO
 */
$di->set('pdo', function () use ($di) {
    $config = $di->getConfigs();
    $pdo = new PDO($config->pdo->dsn, $config->pdo->username, $config->pdo->password);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    return $pdo;
}, true);

/**
 * Http Request
 */
$di->set('http_request', function () {
    return new \Phalcon\Http\Request();
}, true);

/**
 * Http Response
 */
$di->set('http_response', function () {
    return new \Phalcon\Http\Response();
}, true);

/**
 * Memcached
 */
$di->set('memcached', function () use ($di) {
    $memcached = new \Memcached(rand(1, 5));
    if (!count($memcached->getServerList())) {
        $memcached->addServers($di->getConfigs()->memcached->servers->toArray());
        $memcached->setOptions([
            Memcached::OPT_TCP_NODELAY => true,
            Memcached::OPT_NO_BLOCK => true,
            Memcached::OPT_CONNECT_TIMEOUT => 100
        ]);
    }

    return $memcached;
}, true);

return $di;
