<?php

namespace Application\Web;

use Application\Di;
use Phalcon\Http\Response;
use Phalcon\Mvc\Router;

class Dispatcher
{
    const CONTROLLER_NAMESPACE_PREFIX = 'Application\Web';

    /**
     * @var
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

    public function dispatch()
    {
        $router = $this->getDi()->getRouter();
        $this->sendControllerResponse(
            $this->findCurrentController($router),
            $router->getActionName(),
            $router->getParams()
        );
    }

    /**
     * @param Router $router
     * @return mixed
     */
    protected function findCurrentController(Router $router)
    {
        $router->handle();
        $className = $this->generateClassName($router->getModuleName(), $router->getControllerName());
        return new $className($this->getDi());
    }

    /**
     * @param $moduleName
     * @param $controllerName
     * @return string
     */
    public function generateClassName($moduleName, $controllerName)
    {
        $moduleName = ucwords($moduleName);
        $controllerName = str_replace(' ', '', ucwords(str_replace('-', ' ', $controllerName)));
        return Dispatcher::CONTROLLER_NAMESPACE_PREFIX . '\\' . $moduleName . '\\' . $controllerName;
    }

    /**
     * @param BaseController $controller
     * @param $actionName
     * @param array $params
     */
    public function sendControllerResponse(BaseController $controller, $actionName, array $params = [])
    {
        $response = $controller->preDispatch($params);
        if (!$response instanceof Response) {
            $response = $controller->$actionName($params);
            $postResponse = $controller->postDispatch($params);
            if ($postResponse instanceof Response) {
                $response = $postResponse;
            }

            if (!$response instanceof Response) {
                $response = $this->getDi()->getHttpResponse();
            }
        }

        $response->send();
    }
}
