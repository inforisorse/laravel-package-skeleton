<?php

declare(strict_types=1);

namespace Inforisorse\SmsGateway\Exceptions;

/**
 *  Empty recipient list exception
 */
class ApiClientLoginFailedException extends SmsGatewayException
{
    public function __construct(string $drivername, string $httpcode, string $apiurl, string $username, int $code = 0, \Throwable $previous = null)
    {
        $msgDesription = __('smsgateway::exceptions.api_client_login_failed', ['apiurl' => $apiurl, 'httpcode' => $httpcode, 'drivername' => $drivername, 'username' => $username]);
        parent::__construct($msgDesription, $code, $previous);
    }
}
