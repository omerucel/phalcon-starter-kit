<?php

$environment = strtolower(getenv('APPLICATION_ENV'));

// Check app_env setting.
if (!$environment) {
    throw new \Exception('APPLICATION_ENV : empty');
}

/**
 * @var \Application\Di $di
 */
$di = include(realpath(__DIR__ . '/../') . '/configs/bootstrap.php');
$di->getDispatcher()->dispatch();
