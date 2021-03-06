<?php

namespace Application\Logger;

interface Logger
{
    public function info($message);
    public function notice($message);
    public function debug($message);
    public function warning($message);
    public function error($message);
    public function critical($message);
    public function alert($message);
    public function emergency($message);
    public function log($type, $message);
}
