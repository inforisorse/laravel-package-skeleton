<?php

declare(strict_types=1);
/**
 * Copyright (C) 2023, Consulanza Informatica.
 */

namespace Inforisorse\SmsGateway\Drivers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Inforisorse\SmsGateway\Contracts\AbstractSmsDriver;
use Inforisorse\SmsGateway\Exceptions\ApiClientLoginFailedException;
use Inforisorse\SmsGateway\Exceptions\InvalidMessageQualityException;
use Inforisorse\SmsGateway\Interfaces\SmsDriverInterface;
use Symfony\Component\HttpFoundation\Response;

class Skebby extends AbstractSmsDriver implements SmsDriverInterface
{
    const MESSAGE_QUALITY_HIGH = 'GP';

    const MESSAGE_QUALITY_MEDIUM = 'TI';

    const MESSAGE_QUALITY_LOW = 'SI';

    protected $driverName = 'skebby';

    private $userKey = '';

    private $sessionKey = '';

    private $accessToken = '';

    public function __construct(
        private Client $httpClient
    ) {
    }

    protected function driverConfigValue(string $path)
    {
        return config(sprintf('%s.%s', 'smsgateway.drivers.skebby', $path));
    }

    protected function getApiUrl(string $endpointName): string
    {
        return $this->driverConfigValue('apiBaseUrl');
    }

    /**
     * Returns the values user-key:session-key for token authentication with SESSION KEY with headers
     * "user_key" and "Session_key". Session key will expire after 5 minutes after last access.
     *
     * @return $this
     */
    protected function login(): self
    {
        $apiUrl = $this->getApiUrl('login');
        $user = $this->driverConfigValue('auth.login.user');
        $password = $this->driverConfigValue('auth.login.password');

        $response = Http::withBasicAuth($user, $password)->get($apiUrl);
        if ($response->status() != Response::HTTP_OK) {
            throw new ApiClientLoginFailedException($this->driverName, strval($response->status()), $apiUrl, $user);
        }
        $res = excplode(';', $response->body());
        $this->userKey = $res[0];
        $this->sessionKey = $res[1];

        return $this;
    }

    /**
     * Returns the values user-key:session-key for token authentication with SESSION KEY with headers
     * "user_key" and "access_token".
     *
     * @return $this
     */
    protected function token(): self
    {
        $apiUrl = $this->getApiUrl('token');
        $user = $this->driverConfigValue('auth.login.user');
        $password = $this->driverConfigValue('auth.login.password');

        $response = Http::withBasicAuth($user, $password)->get($apiUrl);
        if ($response->status() != Response::HTTP_OK) {
            throw new ApiClientLoginFailedException($this->driverName, strval($response->status()), $apiUrl, $user);
        }
        $res = excplode(';', $response->body());
        $this->userKey = $res[0];
        $this->accessToken = $res[1];

        return $this;
    }

    protected function preparePayload(): self
    {
        return $this;
    }

    protected function apiSendMessage(): self
    {
        return $this;
    }

    public function setMessageQuality(string $message_quality): self
    {
        if (! in_array($message_quality, [self::MESSAGE_QUALITY_LOW, self::MESSAGE_QUALITY_MEDIUM, self::MESSAGE_QUALITY_HIGH])) {
            throw new InvalidMessageQualityException($message_quality);
        }
        $this->message_quality = $message_quality;

        return $this;
    }
}
