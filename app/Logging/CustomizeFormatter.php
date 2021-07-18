<?php

namespace App\Logging;

use Illuminate\Log\Logger;

class CustomizeFormatter
{
    /**
     * 自定义给定的日志实例。
     *
     * @param Logger $logger
     * @return void
     */
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new JsonFormatter());
        }
    }
}
