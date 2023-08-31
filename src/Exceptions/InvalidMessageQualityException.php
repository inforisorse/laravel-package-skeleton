<?php
declare(strict_types=1);

namespace Inforisorse\SmsGateway\Exceptions;


/**
 *  Empty recipient list exception
 */
class InvalidMessageQualityException extends SmsGatewayException
{
    public function __construct(string $quality = "", int $code = 0, ?\Throwable $previous = null)
    {
        $msgDesription = __(' smsgateway::exceptions.invalid_message_quality', ['quality' => $quality]);
        parent::__construct($msgDesription, $code, $previous);
    }
}
