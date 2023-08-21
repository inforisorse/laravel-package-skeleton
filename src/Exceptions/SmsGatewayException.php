<?php
declare(strict_types=1);

namespace Inforisorse\SmsGateway\Exceptions;

use Exception;

class SmsGatewayException extends Exception
{
    public function errorMessage()
    {
        $msgPrefix = __('smsgateway::exceptions.prefix');
        $msgIntro = __('smsgateway::exceptions.intro');
        return sprintf('[%8s] %s: %s', $msgPrefix, $msgIntro, $this->getMessage());
    }
}
