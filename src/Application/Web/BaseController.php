<?php

namespace Application\Web;

use Application\Di;
use Phalcon\Http\Response;

abstract class BaseController
{
    /**
     * @var Di
     */
    protected $dependencyInjection;

    /**
     * @param Di $dependencyInjection
     */
    public function __construct(Di $dependencyInjection)
    {
        $this->dependencyInjection = $dependencyInjection;
    }

    /**
     * @return Di
     */
    public function getDi()
    {
        return $this->dependencyInjection;
    }

    /**
     * @param array $params
     * @return null
     */
    public function preDispatch(array $params = [])
    {
        $this->registerErrorHandlers();
        return null;
    }

    /**
     * @param array $params
     * @return null
     */
    public function postDispatch(array $params = [])
    {
        return null;
    }

    public function registerErrorHandlers()
    {
        set_error_handler(array($this, 'errorHandler'));
        set_exception_handler(array($this, 'handleException'));
        register_shutdown_function(array($this, 'fatalErrorHandler'));
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
     * @return mixed
     */
    abstract public function handleException(\Exception $exception);

    /**
     * @param $errStr
     * @param $errNo
     * @param $errFile
     * @param $errLine
     * @return mixed
     */
    abstract public function handleFatalError($errStr, $errNo, $errFile, $errLine);


    /**
     * @param array $result
     * @param int $status
     * @param array $headers
     * @return Response
     */
    protected function toJson(array $result, $status = 200, $headers = array())
    {
        $response = $this->getDi()->getHttpResponse();
        $response->setJsonContent($result)
            ->setStatusCode($status, '');

        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $this->setHeaders($response, $headers);

        return $response;
    }

    /**
     * @param $result
     * @param int $status
     * @param array $headers
     * @return Response
     */
    protected function toPlainText($result, $status = 200, $headers = array())
    {
        $response = $this->getDi()->getHttpResponse();
        $response->setContent($result)
            ->setStatusCode($status, '');

        $headers['Content-Type'] = 'text/plain; charset=utf-8';
        $this->setHeaders($response, $headers);

        return $response;
    }

    /**
     * @param Response $response
     * @param array $headers
     * @return Response
     */
    protected function setHeaders(Response $response, $headers = array())
    {
        $response->setHeader('X-App-Request-Id', $this->getDi()->getConfigs()->req_id);
        foreach ($headers as $name => $value) {
            $response->setHeader($name, $value);
        }
    }
}
