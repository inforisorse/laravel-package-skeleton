<?php
declare(strict_types=1);

namespace Inforisorse\SmsGateway\Exceptions;


/**
 *  Empty recipient list exception
 */
class EmptyMessageException extends SmsGatewayException
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $msgDesription = __(' smsgateway::exceptions.empty_message');
        parent::__construct($msgDesription, $code, $previous);
    }
}
