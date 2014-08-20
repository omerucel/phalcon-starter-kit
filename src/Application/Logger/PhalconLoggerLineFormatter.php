<?php

namespace Application\Logger;

use Phalcon\Logger\Formatter\Line;

class PhalconLoggerLineFormatter extends Line
{
    protected $logCounter = 0;

    /**
     * @var string
     */
    protected $reqId = '';

    /**
     * @param string $message
     * @param int $type
     * @param int $timestamp
     * @param array $context
     * @return mixed|string
     */
    public function format($message, $type, $timestamp, $context)
    {
        $this->logCounter++;
        $output = parent::format($message, $type, $timestamp, $context);
        $output = str_replace('%counter%', $this->logCounter, $output);
        $output = str_replace('%req-id%', $this->reqId, $output);

        return $output;
    }

    /**
     * @param int $logCounter
     */
    public function setLogCounter($logCounter)
    {
        $this->logCounter = $logCounter;
    }

    /**
     * @param string $reqId
     */
    public function setReqId($reqId)
    {
        $this->reqId = $reqId;
    }
}
