<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Illuminate\Support\Str;
use Monolog\Formatter\LineFormatter;

class CustomizeFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param Logger $logger
     *
     * @return void
     */
    public function __invoke (Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler)
        {
            $handler->setFormatter(new LineFormatter(
                                       '[%datetime%] %channel%.%level_name%: ' . PHP_EOL .
                                       '%message% ' . PHP_EOL . '%context% ' . PHP_EOL . '%extra%' .
                                       Str::repeat('=', 150) . PHP_EOL,
                                       'Y-m-d H:i:s', true, true, true
                                   ));
        }
    }
}