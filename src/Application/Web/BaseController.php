<?php

namespace Application\Web;

use Application\Di;
use Application\ErrorCatcher;
use Phalcon\Http\Response;

abstract class BaseController
{
    /**
     * @var Di
     */
    protected $dependencyInjection;

    /**
     * @var ErrorCatcher
     */
    protected $errorCatcher;

    /**
     * @param Di $dependencyInjection
     */
    public function __construct(Di $dependencyInjection)
    {
        $this->dependencyInjection = $dependencyInjection;
        $this->getErrorCatcher()->register();
    }

    /**
     * @return Di
     */
    public function getDi()
    {
        return $this->dependencyInjection;
    }

    /**
     * @return ErrorCatcher
     */
    protected function getErrorCatcher()
    {
        if ($this->errorCatcher == null) {
            $this->errorCatcher = new ErrorCatcher($this->getDi()->getDefaultLogger());
        }
        return $this->errorCatcher;
    }

    /**
     * @param array $params
     * @return null
     */
    public function preDispatch(array $params = [])
    {
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
