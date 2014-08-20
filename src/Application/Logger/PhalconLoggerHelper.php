<?php

namespace Application\Logger;

use Application\Di;
use Phalcon\Logger\Adapter\File;

class PhalconLoggerHelper
{
    /**
     * @var Di
     */
    protected $dependencyInjection;

    /**
     * @var array
     */
    protected $loggers = [];

    /**
     * @param Di $dependencyInjection
     */
    public function __construct(Di $dependencyInjection)
    {
        $this->dependencyInjection = $dependencyInjection;
    }

    /**
     * @return \Application\Di
     */
    public function getDi()
    {
        return $this->dependencyInjection;
    }

    /**
     * @return Logger
     */
    public function getDefaultLogger()
    {
        return $this->getLogger($this->getDi()->getConfigs()->logger->default_name);
    }

    /**
     * @param $name
     * @return Logger
     */
    public function getLogger($name)
    {
        if (!isset($this->loggers[$name])) {
            $filePath = $this->getDi()->getConfigs()->logger->path . '/' . $name . '-' . date('Y-m-d') . '.log';
            $logger = new File($filePath);
            $logger->setLogLevel($this->getDi()->getConfigs()->logger->level);
            $logger->setFormatter($this->createLineFormatter());

            $this->loggers[$name] = new PhalconLoggerProxy($logger);
        }

        return $this->loggers[$name];
    }

    /**
     * @return PhalconLoggerLineFormatter
     */
    protected function createLineFormatter()
    {
        $lineFormatter = new PhalconLoggerLineFormatter(
            $this->getDi()->getConfigs()->logger->line_format,
            $this->getDi()->getConfigs()->logger->datetime_format
        );
        $lineFormatter->setReqId($this->getDi()->getConfigs()->req_id);
        return $lineFormatter;
    }

    /**
     * Özellikle gün aşırı çalışan cron scriptlerinde bu metod kullanılarak log dosyasının değiştirilmesi sağlanıyor.
     */
    public function clearLoggers()
    {
        foreach ($this->loggers as $name => $value) {
            unset($value);
        }

        $this->loggers = array();
    }
}
