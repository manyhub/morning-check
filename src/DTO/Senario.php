<?php

namespace App\DTO;

use JMS\Serializer\Annotation\Type;

class Senario
{
    /**
     * @var string
     * @Type("string")
     */
    private $uri;

    /**
     * @var string[]
     * @Type("array <string, string>")
     */
    private $form_data;

    /**
     * @var string
     * @Type("string")
     */
    private $needle;

    /**
     * @var string
     * @type("string")
     */
    private $title;

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string[]
     */
    public function getFormData(): array
    {
        return $this->form_data;
    }

    /**
     * @return string
     */
    public function getNeedle(): string
    {
        return $this->needle;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
