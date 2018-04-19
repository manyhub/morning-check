<?php
namespace App\Notifier;

interface NotifierInterface
{
    public function send(array $result);

    public function getName();
}
