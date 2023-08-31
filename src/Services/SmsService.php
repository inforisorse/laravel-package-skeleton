<?php
declare(strict_types=1);


namespace Inforisorse\SmsGateway\Services;

class SmsService
{
    public function sendMessage(string $message, string|array $recipients, ?string $driver = null, string $sender='')
    {
        $driver = smsGateway()->send($message)->via($driver)->to($recipients)->from($sender)->dispatch();
    }
}
