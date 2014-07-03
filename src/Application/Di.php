<?php

namespace Application;

use Application\Logger\Helper;
use Application\Web\Dispatcher;
use Phalcon\Config\Adapter\Php;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Logger\Adapter\File;
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
     * @return File
     */
    public function getDefaultLogger()
    {
        return $this->getLoggerHelper()->getDefaultLogger();
    }

    /**
     * @return Helper
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
