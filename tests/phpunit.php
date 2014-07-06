<?php

/**
 * @var \Application\Di $di
 */
$environment = 'testing';
$di = include(realpath(__DIR__ . '/../') . '/configs/bootstrap.php');
$loader->add('Pozitim\\', realpath(__DIR__ . '/unit/src/'));

include_once 'DiSingleton.php';
DiSingleton::getInstance()->setDi($di);
