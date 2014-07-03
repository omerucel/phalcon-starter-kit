<?php

namespace Application\Web;

class Error extends \Exception
{
    /**
     * @var int
     */
    private $httpCode = 500;

    /**
     * @param string $message
     * @param int $code
     * @param int $httpCode
     * @param \Exception $previous
     */
    public function __construct($message, $code = 0, $httpCode = 0, \Exception $previous = null)
    {
        $this->httpCode = $httpCode;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    public function __toString()
    {
        return '[' . $this->getCode() . '] ' . $this->getMessage() . ' ' . $this->getTraceAsString();
    }
}
