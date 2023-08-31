<?php
declare(strict_types=1);


namespace Inforisorse\SmsGateway\Drivers;

use Inforisorse\SmsGateway\Exceptions\ApiClientLoginFailedException;
use Inforisorse\SmsGateway\Exceptions\InvalidMessageQualityException;
use Inforisorse\SmsGateway\Exceptions\SendSmsNotCreatedException;
use Inforisorse\SmsGateway\Traits\NamespaceUtils;
use Symfony\Component\HttpFoundation\Response;

class CurlSkebby
{

    use NamespaceUtils;
    use UseCurlForApi;

    const MESSAGE_QUALITY_HIGH = "GP";
    const MESSAGE_QUALITY_MEDIUM = "TI";
    const MESSAGE_QUALITY_LOW = "SI";

    const HEADER_USER_KEY = 'user_key';
    const HEADER_TOKEN_NAME = 'Access_token';

    private ?string $user_key = null; // user key for authentication
    private ?string $access_token = null;  // user token for authentication

    private ?string $message_quality = '';

    /**
     * @return void
     */
    protected function boot()
    {
        $this->message_quality = $this->getDriverParam('message_type', self::MESSAGE_QUALITY_LOW);
    }

    /**
     * @param string $message_type
     * @return $this
     */
    public function setMessageQuality(string $message_quality): self
    {
        if (! in_array($message_quality, [self::MESSAGE_QUALITY_LOW, self::MESSAGE_QUALITY_MEDIUM, self::MESSAGE_QUALITY_HIGH])) {
            throw new InvalidMessageQualityException($message_quality);
        }
        $this->message_quality = $message_quality;
        return $this;
    }


    /**
     * Get the user API KEY
     * @return string
     */
    protected function getUserKey(): string
    {
        return $this->user_key ?: config($this->getDriverParam(self::HEADER_USER_KEY));
    }

    /**
     * Get the user API TOKEN
     * @return string
     */
    protected function getAccessToken(): string
    {
        return $this->access_token ?: config($this->getDriverParam(self::HEADER_TOKEN_NAME));
    }


    /**
     * Execute login and get headers auth values. If succesfull saves user_key and access_token
     *
     * @return $this
     * @throws ApiClientLoginFailedException
     */
    protected function login(): self
    {
        $this->initHandler();
        $this->setRequestUrl('token');
        $this->setLoginHeaders();
        $this->setLoginAuth();
        $this->setReturnTransfer();

        $this->sendRequestToProvider();
        $this->closeHandle();

        if ($this->httpStatusCode != Response::HTTP_OK) {
            throw new ApiClientLoginFailedException($this->cleanClassName(), strval($this->httpStatusCode), $this->getApiBaseUrl(), $this->getUsername());
        }

        $auth = explode(";", $this->response);
        $this->user_key = $auth[0];
        $this->access_token = $auth[1];

        return $this;
    }

    /**
     * Send SMS via API request
     *
     * @param $payLoad
     * @return $this
     * @throws SendSmsNotCreatedException
     * @throws \Inforisorse\SmsGateway\Exceptions\CantAddParamsToEmptyUrlException
     * @throws \Inforisorse\SmsGateway\Exceptions\CurlHandleNotInitializadException
     */
    protected function sendSMS($payLoad): self
    {
        $this->initHandler();
        $this->setRequestUrl('sms');
        $this->setHeaders(true);
        $this->setReturnTransfer();

        $this->setPostMethod();
        $this->setPostFields($payLoad);

        $this->sendRequestToProvider();
        $this->closeHandle();

        if ($this->httpStatusCode != Response::HTTP_CREATED) {
            throw new SendSmsNotCreatedException(strval($this->httpStatusCode), $this->cleanClassName());
        }
        return $this;
    }

    /**
     * Prepare HTTP headers for login
     *
     * @return $this
     */
    protected function setLoginHeaders(): self
    {
        $curlHeaders = [
            $this->makeHeader('Content-type', 'application/json')
        ];
        if (!curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, $curlHeaders)) {
            // throw new CurlSetHeadersException();
        }
        return $this;
    }

    /**
     * Set user:pass authentication for login
     * @return $this
     */
    protected function setLoginAuth(): self
    {
        curl_setopt($this->curlHandle, CURLOPT_USERPWD, sprintf("%s:%s", $this->getUsername(), $this->getPassword()));
        return $this;
    }

    /**
     * Make payload array for sendigg SMS
     *
     * @return array
     */
    protected function makePayload(): array
    {
        return [
            "message_type" => $this->getMessageType(),
            "message" => $this->body,
            "recipient" => $this->recipients,
            "sender" => null,     // Place here a custom sender if desired
            "returnCredits" => false,
        ];
    }

    /**
     * Returns the current message quality setting
     *
     * @return string
     */
    protected function getMessageType(): string
    {
        return $this->message_quality;
    }


}
