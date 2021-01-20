<?php
namespace app\src\service;

use app\src\entities\trigger\Trigger;

interface INotificationService
{
    public function send(Trigger $trigger, $user, array $message);
}