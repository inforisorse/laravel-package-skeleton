<?php

declare(strict_types=1);

namespace Inforisorse\SmsGateway\Exceptions;

/**
 *  Empty recipient list exception
 */
class CurlHandleNotInitializadException extends SmsGatewayException
{
    public function __construct(string $drivername, string $method, int $code = 0, \Throwable $previous = null)
    {
        $msgDesription = __('smsgateway::exceptions.cant_add_params_to_emty_url', ['drivername' => $drivername, 'metdod' => $method]);
        parent::__construct($msgDesription, $code, $previous);
    }
}
