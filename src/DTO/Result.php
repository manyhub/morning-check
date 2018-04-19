<?php
namespace App\DTO;

class Result
{
    /** @var  string */
    private $appName;

    /** @var  bool */
    private $status;

    /** @var  \DateInterval  */
    private $duration;

    /**
     * Result constructor.
     * @param string $appName
     * @param bool $status
     * @param \DateInterval $duration
     */
    public function __construct(string $appName,bool $status, \DateInterval $duration)
    {
        $this->appName = $appName;
        $this->status = $status;
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getAppName(): string
    {
        return $this->appName;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @return \DateInterval
     */
    public function getDuration(): \DateInterval
    {
        return $this->duration;
    }
}
