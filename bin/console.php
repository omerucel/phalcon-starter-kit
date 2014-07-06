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
$di->getConfigs()->logger->default_name = 'console';

$consoleApp = new \Symfony\Component\Console\Application(
    $di->getConfigs()->console_application->name
);
$consoleApp->getHelperSet()->set(new \Application\Console\DiHelper($di));


/**
 * Diğer komutları buradan ekleyebilirsiniz:
 *
 * $symfonyConsoleApp->add(new \Application\Console\MyCommand());
 */

/**
 * Doctrine ile ilgili komutlar özel olarak ekleniyor.
 */
$mysqlConfig = $di->getConfigs()->mysql_connection;
$doctrineConn = \Doctrine\DBAL\DriverManager::getConnection(
    array(
        'driver' => 'pdo_mysql',
        'host' => $mysqlConfig->host,
        'dbname' => $mysqlConfig->dbname,
        'user' => $mysqlConfig->username,
        'password' => $mysqlConfig->password
    )
);
$consoleApp->getHelperSet()->set(new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($doctrineConn));
$consoleApp->getHelperSet()->set(new \Symfony\Component\Console\Helper\DialogHelper(), 'dialog');
$consoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand());
$consoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand());
$consoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand());
$consoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand());
$consoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\LatestCommand());
$consoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand());
$consoleApp->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand());

$consoleApp->run();
