<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/../'));
}

ini_set('display_errors', false);
date_default_timezone_set('Europe/Istanbul');

/**
 * Init
 */
$configs = array(
    'base_path' => BASE_PATH,
    'req_id' => uniqid(gethostname() . '-REQ-')
);

/**
 * Console Application
 */
$configs['console_application'] = array();
$configs['console_application']['name'] = 'Console Application';

/**
 * PDO Service Configs
 */
$configs['pdo'] = array();
$configs['pdo']['dsn'] = 'mysql:host=127.0.0.1;dbname=sample;charset=utf8';
$configs['pdo']['username'] = 'root';
$configs['pdo']['password'] = '';

/**
 * Memcached Service Configs
 */
$configs['memcached'] = array();
$configs['memcached']['servers'] = array(array('127.0.0.1', 11211));

/**
 * Graphite & Statd
 */
$configs['statd'] = array();
$configs['statd']['host'] = '127.0.0.1';
$configs['statd']['port'] = 8125;
$configs['statd']['namespace'] = 'app.' . gethostname();

/**
 * levels:
 *
 * 7 DEBUG
 * 6 INFO
 * 5 NOTICE
 * 4 WARNING
 * 3 ERROR
 * 2 CRITICAL
 * 1 ALERT
 * 0 EMERGENCY
 *
 */
$configs['logger'] = array();
$configs['logger']['default_name'] = 'app';
$configs['logger']['line_format'] = "[%date%] [%req-id%-%counter%] [%type%] %message%";
$configs['logger']['path'] = realpath(BASE_PATH . '/log');
$configs['logger']['level'] = 6;
$configs['logger']['datetime_format'] = 'Y-m-d H:i:s';

return $configs;
