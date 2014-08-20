<?php

namespace Application\Logger;

use Phalcon\Logger\AdapterInterface;

class PhalconLoggerProxy implements Logger
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function info($message)
    {
        $this->log('info', $message);
    }

    public function notice($message)
    {
        $this->log('notice', $message);
    }

    public function debug($message)
    {
        $this->log('debug', $message);
    }

    public function warning($message)
    {
        $this->log('warning', $message);
    }

    public function error($message)
    {
        $this->log('error', $message);
    }

    public function critical($message)
    {
        $this->log('critical', $message);
    }

    public function alert($message)
    {
        $this->log('alert', $message);
    }

    public function emergency($message)
    {
        $this->log('emergency', $message);
    }

    public function log($type, $message)
    {
        if ($message instanceof \Exception) {
            $message = '[' . $message->getCode() . '] ' . $message->getMessage() . ' ' . $message->getTraceAsString();
            $message = str_replace("\n", '', $message);
        }
        $this->adapter->log($type, $message);
    }
}
