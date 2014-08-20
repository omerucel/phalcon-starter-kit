<?php

namespace Application;

use Application\Logger\Logger;
use Application\Logger\PhalconLoggerHelper;
use Application\Web\Dispatcher;
use Composer\Autoload\ClassLoader;
use Phalcon\Config\Adapter\Php;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Router;

class Di extends \Phalcon\DI
{
    /**
     * @return Php
     */
    public function getConfigs()
    {
        return $this->getShared('configs');
    }

    /**
     * @return ClassLoader
     */
    public function getClassLoader()
    {
        return $this->getShared('class_loader');
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->getShared('router');
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        return $this->getShared('dispatcher');
    }

    /**
     * @return Request
     */
    public function getHttpRequest()
    {
        return $this->getShared('http_request');
    }

    /**
     * @return Response
     */
    public function getHttpResponse()
    {
        return $this->getShared('http_response');
    }

    /**
     * @return Logger
     */
    public function getDefaultLogger()
    {
        return $this->getLoggerHelper()->getDefaultLogger();
    }

    /**
     * @return PhalconLoggerHelper
     */
    public function getLoggerHelper()
    {
        return $this->getShared('logger_helper');
    }

    /**
     * @return \PDO
     */
    public function getPDO()
    {
        return $this->getShared('pdo');
    }

    /**
     * @return \Memcached
     */
    public function getMemcached()
    {
        return $this->getShared('memcached');
    }
}
