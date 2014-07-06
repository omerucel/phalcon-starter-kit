<?php

class DiSingleton
{
    /**
     * @var DiSingleton
     */
    protected static $instance;

    /**
     * @var \Application\Di
     */
    protected $dependencyInjection;

    /**
     * @return DiSingleton
     */
    public static function getInstance()
    {
        if (static::$instance == null) {
            static::$instance = new DiSingleton();
        }

        return static::$instance;
    }

    /**
     * @param \Application\Di $dependencyInjection
     */
    public function setDi($dependencyInjection)
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
}
