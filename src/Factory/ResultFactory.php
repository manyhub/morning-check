<?php

namespace App\Factory;

use App\DTO\Result;

class ResultFactory
{
    /**
     * @param string $appName
     * @param bool $status
     * @param \DateInterval $duration
     * @return Result
     */
    public static function create(string $appName, bool $status, \DateInterval $duration): Result
    {
        return new Result($appName, $status, $duration);
    }
}
