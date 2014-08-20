<?php

namespace Application\Web;

use Application\Web\Exception\MethodNotAllowedException;
use Phalcon\Http\Response;

abstract class BaseRestController extends BaseController
{
    /**
     * @param $name
     * @param $arguments
     * @throws Error
     */
    public function __call($name, $arguments)
    {
        $this->getDi()->getDefaultLogger()->warning(
            'Method(' . $name . ') not allowed. args : ' . json_encode($arguments)
        );
        throw new MethodNotAllowedException(0); // TODO : set an error code instead of zero!
    }

    /**
     * @param array $params
     * @return null
     */
    public function preDispatch(array $params = [])
    {
        $this->getErrorCatcher()->setWarningCallback(array($this, 'handleWarningError'));
        $this->getErrorCatcher()->setFatalCallback(array($this, 'handleFatalError'));
        return null;
    }

    /**
     * @param array $params
     */
    public function handle(array $params = [])
    {
        $request = $this->getDi()->getHttpRequest();
        $method = strtoupper($request->get('_method'));
        switch ($method) {
            case 'GET':
            case 'POST':
            case 'DELETE':
            case 'PUT':
                break;
            default:
                $method = strtoupper($request->getMethod());
                break;
        }

        return $this->$method($params);
    }

    public function handleWarningError(Error $exception)
    {
        $result = array(
            'meta' => array(
                'requestId' => $this->getDi()->getConfigs()->req_id,
                'httpStatusCode' => $exception->getHttpCode(),
                'errorMessage' => $exception->getMessage(),
                'errorCode' => $exception->getCode()
            )
        );

        $this->toJson($result, $exception->getHttpCode())->send();
    }

    public function handleFatalError()
    {
        $result = array(
            'meta' => array(
                'requestId' => $this->getDi()->getConfigs()->req_id,
                'httpStatusCode' => 500,
                'errorMessage' => 'An error occurred.',
                'errorCode' => 0  // TODO : set an error code instead of zero!
            )
        );

        $this->toJson($result, 500)->send();
    }
}
