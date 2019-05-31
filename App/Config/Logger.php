<?php

namespace App\Config;

use EasySwoole\Component\Singleton;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonoLogger;

class Logger
{
    use Singleton;

    protected $logDir = '/data/log/easyswoole/';
    protected $logFile = 'debug.log';
    protected $logFilePostFix = '.log';

    /**
     * @param string $message
     * @param array $contex
     * @param string $channel
     * @throws \Exception
     */
    public function log($message = '', $contex = [], $channel = 'ES')
    {
        $this->initLogger($channel)->addInfo($message, $contex);
    }

    /**
     * @param string $channel
     * @throws \Exception
     * @return \Monolog\Logger
     */
    protected function initLogger($channel = 'ES')
    {
        $logger = new MonoLogger($channel);
        $handler = new StreamHandler($this->logDir. $channel. $this->logFilePostFix);
        $logger->pushHandler($handler);


        return $logger;

    }



}