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

    /** @var string  */
    private $title;

    /** @var string */
    private $screen;


    public function __construct(
        string $appName,
        bool $status,
        \DateInterval $duration,
        string $title,
        string $screen
    )
    {
        $this->appName = $appName;
        $this->status = $status;
        $this->duration = $duration;
        $this->title = $title;
        $this->screen = $screen;
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

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getScreen():string
    {
        return $this->screen;
    }
}
