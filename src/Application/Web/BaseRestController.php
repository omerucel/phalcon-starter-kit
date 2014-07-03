<?php

namespace Application\Web;

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
        throw new Error('Method not allowed.', 0, 405); // TODO : set an error code instead of zero!
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

    /**
     * @param \Exception $exception
     * @return mixed
     */
    public function handleException(\Exception $exception)
    {
        /**
         * @var \Exception|Error $exception
         */
        $message = '[' . $exception->getCode() . '] ' . $exception->getMessage() . ' ' . $exception->getTraceAsString();
        $message = str_replace("\n", '', $message);

        if ($exception instanceof Error && $exception->getHttpCode() < 500) {
            $this->getDi()->getDefaultLogger()->warning($message);

            $result = array(
                'meta' => array(
                    'requestId' => $this->getDi()->getConfigs()->req_id,
                    'httpStatusCode' => $exception->getHttpCode(),
                    'errorMessage' => $exception->getMessage(),
                    'errorCode' => $exception->getCode()
                )
            );

            $this->toJson($result, $exception->getHttpCode())->send();
        } else {
            $this->getDi()->getDefaultLogger()->emergency($message);
            $this->fatalErrorResponse()->send();
        }
    }

    /**
     * @param $errStr
     * @param $errNo
     * @param $errFile
     * @param $errLine
     * @return mixed
     */
    public function handleFatalError($errStr, $errNo, $errFile, $errLine)
    {
        $message = $errNo . ' ' . $errStr . ' ' . $errFile . ':' . $errLine;
        $message = str_replace("\n", '', $message);
        $this->getDi()->getDefaultLogger()->emergency($message);

        return $this->fatalErrorResponse();
    }

    /**
     * @return Response
     */
    protected function fatalErrorResponse()
    {
        $result = array(
            'meta' => array(
                'requestId' => $this->getDi()->getConfigs()->req_id,
                'httpStatusCode' => 500,
                'errorMessage' => 'An error occurred.',
                'errorCode' => 0  // TODO : set an error code instead of zero!
            )
        );

        return $this->toJson($result, 500);
    }
}
