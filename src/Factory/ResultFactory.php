<?php

namespace App\Factory;

use App\DTO\Result;

class ResultFactory
{
    /**
     * @param string $appName
     * @param bool $status
     * @param \DateInterval $duration
     * @param string $title
     * @param string $screen
     * @return Result
     */
    public static function create(string $appName, bool $status, \DateInterval $duration, string $title, string $screen): Result
    {
        return new Result($appName, $status, $duration, $title, $screen);
    }
}
