<?php

namespace Application;

use Application\Logger\Logger;

class ErrorCatcher
{
    /**
     * @var Logger
     */
    protected $logger;
    protected $fatalCallback = null;
    protected $warningCallback = null;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function register()
    {
        set_error_handler(array($this, 'errorHandler'));
        set_exception_handler(array($this, 'handleException'));
        register_shutdown_function(array($this, 'fatalErrorHandler'));
    }

    /**
     * @param null $onFatalCallback
     */
    public function setFatalCallback($onFatalCallback)
    {
        $this->fatalCallback = $onFatalCallback;
    }

    /**
     * @return null
     */
    protected function onFatalCallback()
    {
        if ($this->fatalCallback != null) {
            call_user_func_array($this->fatalCallback, []);
        }
    }

    /**
     * @param null $onWarningCallback
     */
    public function setWarningCallback($onWarningCallback)
    {
        $this->warningCallback = $onWarningCallback;
    }

    /**
     * @param \Exception $exception
     */
    protected function onWarningCallback(\Exception $exception)
    {
        if ($this->warningCallback != null) {
            call_user_func_array($this->warningCallback, [$exception]);
        }
    }

    /**
     * Bir hata oluştuğunda bu metod tetiklenir.
     *
     * @param $errNo
     * @param $errStr
     * @param $errFile
     * @param $errLine
     * @throws \ErrorException
     */
    public function errorHandler($errNo, $errStr, $errFile, $errLine)
    {
        throw new \ErrorException($errStr, $errNo, 0, $errFile, $errLine);
    }

    /**
     * Ölümcül bir hata oluştuğunda bu metod tetiklenir.
     *
     * @return mixed
     */
    public function fatalErrorHandler()
    {
        $error = error_get_last();

        if ($error !== null) {
            $errNo = $error["type"];
            $errFile = $error["file"];
            $errLine = $error["line"];
            $errStr = $error["message"];

            $this->handleFatalError($errStr, $errNo, $errFile, $errLine);
        }
    }

    /**
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception)
    {
        if ($exception instanceof Error && $exception->getHttpCode() < 500) {
            $this->logger->warning($exception);
            $this->onWarningCallback($exception);
        } else {
            $this->logger->emergency($exception);
            $this->onFatalCallback();
        }
    }

    /**
     * @param $errStr
     * @param $errNo
     * @param $errFile
     * @param $errLine
     */
    public function handleFatalError($errStr, $errNo, $errFile, $errLine)
    {
        $message = $errNo . ' ' . $errStr . ' ' . $errFile . ':' . $errLine;
        $message = str_replace("\n", '', $message);
        $this->logger->emergency($message);
        $this->onFatalCallback();
    }
}
