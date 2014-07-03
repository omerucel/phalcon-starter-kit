<?php

namespace Application\Console;

use Application\Di;
use Symfony\Component\Console\Helper\Helper;

class DiHelper extends Helper
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
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     *
     * @api
     */
    public function getName()
    {
        return 'di';
    }
}
