<?php

namespace Application\Web\Exception;

use Application\Web\Error;

class MethodNotAllowedException extends Error
{
    /**
     * @var int
     */
    private $httpCode = 405;

    /**
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($code = 0, \Exception $previous = null)
    {
        parent::__construct('Method not allowed!', $code, $previous);
    }
}
