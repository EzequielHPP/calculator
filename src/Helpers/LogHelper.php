<?php

namespace App\Helpers;

use Symfony\Component\HttpKernel\Log\Logger;

class LogHelper
{

    public static function error(\Throwable $exception): void
    {
        (new Logger())->error($exception->getMessage() . ' on ' . $exception->getFile() . ':' . $exception->getLine());
    }
}
