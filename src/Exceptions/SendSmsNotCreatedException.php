<?php
declare(strict_types=1);

namespace Inforisorse\SmsGateway\Exceptions;

/**
 *  Empty recipient list exception
 */
class SendSmsNotCreatedException extends SmsGatewayException
{
    public function __construct(string $httpcode, string $drivername, int $code = 0, ?\Throwable $previous = null)
    {
        $msgDesription = __('smsgateway::exceptions.send_sms_not_created', ['httpcode' => $httpcode, 'drivername' => $drivername]);
        parent::__construct($msgDesription, $code, $previous);
    }
}
