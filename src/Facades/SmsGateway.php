<?php

namespace Inforisorse\SmsGateway\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Inforisorse\SmsGateway\SmsGateway
 */
class SmsGateway extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Inforisorse\SmsGateway\SmsGateway::class;
    }
}
