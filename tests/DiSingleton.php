<?php

class DiSingleton
{
    /**
     * @var DiSingleton
     */
    protected static $instance;

    /**
     * @var \Pozitim\Subs\Di
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
     * @param \Pozitim\Subs\Di $dependencyInjection
     */
    public function setDi($dependencyInjection)
    {
        $this->dependencyInjection = $dependencyInjection;
    }

    /**
     * @return \Pozitim\Subs\Di
     */
    public function getDi()
    {
        return $this->dependencyInjection;
    }
}
