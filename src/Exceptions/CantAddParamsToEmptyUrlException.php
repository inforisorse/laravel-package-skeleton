<?php
declare(strict_types=1);

namespace Inforisorse\SmsGateway\Exceptions;

/**
 *  Empty recipient list exception
 */
class CantAddParamsToEmptyUrlException extends SmsGatewayException
{
    public function __construct(string $drivername, string $api, int $code = 0, ?\Throwable $previous = null)
    {
        $msgDesription = __('smsgateway::exceptions.cant_set_url_to_uninitializad_handler', ['drivername' => $drivername, 'api' => $api]);
        parent::__construct($msgDesription, $code, $previous);
    }
}
