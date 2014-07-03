<?php

namespace Application\Console;

use Application\Di;
use Symfony\Component\Console\Command\Command;

abstract class BaseCommand extends Command
{
    /**
     * @return Di
     */
    public function getDi()
    {
        return $this->getDiHelper()->getDi();
    }

    /**
     * @return DiHelper
     */
    public function getDiHelper()
    {
        return $this->getHelperSet()->get('di');
    }
}
