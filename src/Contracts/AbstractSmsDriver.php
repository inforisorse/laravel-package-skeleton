<?php

declare(strict_types=1);

namespace Inforisorse\SmsGateway\Contracts;

use Illuminate\Support\Arr;
use Inforisorse\SmsGateway\Exceptions\EmptyMessageException;
use Inforisorse\SmsGateway\Exceptions\EmptyRecipientsException;
use Inforisorse\SmsGateway\Interfaces\SmsDriverInterface;

abstract class AbstractSmsDriver implements SmsDriverInterface
{
    protected array $recipients = [];

    protected string $body = '';

    protected ?string $sender = '';

    protected ConfigurationTree $configurationTree;

    public function __construct()
    {
        $this->boot();
    }

    /**
     * Returns the driver's config root
     */
    protected function configNode(): string
    {
        return sprintf('%s.%s', AbstractSmsGateway::smsDriversConfigPath(), strtolower($this->cleanClassName()));
    }

    /**
     * Returns the class name without namespace
     */
    abstract protected function cleanClassName(): string;

    /**
     * Returns the driver's config value for the specified kry
     */
    protected function getDriverParam(string $key, string $default = ''): string
    {
        return config(sprintf('%s.%s', $this->configNode(), $key), $default);
    }

    /**
     * Get SMS provider's API base url from fonfig
     */
    protected function getApiBaseUrl(): string
    {
        return $this->getDriverParam('api_base_url');
    }

    /**
     * Executed in constructor. Allows to customize constructor
     *
     * @return void
     */
    protected function boot()
    {

    }

    /**
     * Set message sender. Any needed check for $sender will be included
     * in checkSender()
     *
     * @return $this
     */
    public function from(?string $sender): self
    {
        $sender = trim($sender);
        $this->checkSender($sender);
        $this->sender = $sender;

        return $this;
    }

    /**
     * Set the message text
     *
     * @return $this
     *
     * @throws EmptyMessageException
     */
    public function message(string $message): self
    {
        $message = trim($message);
        $this->checkMessage($message);
        $this->body = $message;

        return $this;
    }

    /**
     * Add specified recipients to target addresses
     *
     * @return $this
     *
     * @throws EmptyRecipientsException
     */
    public function to(array|string $recipients): self
    {
        $recipients = Arr::wrap($recipients);
        foreach ($recipients as $recipient) {
            $this->recipients[trim($recipient)] = trim($recipient);
        }
        $this->recipients = array_values($this->recipients);

        return $this;
    }

    /**
     * Check sender. Descendants can add formal check and throw appropriate
     * exception if needed
     *
     * @return $this
     */
    protected function checkSender(string $sender): self
    {
        return $this;
    }

    /**
     * Check message body. Descendants can override or extend this method
     * in order to customize message content check
     *
     * @return $this
     */
    protected function checkMessage(string $message): self
    {
        $message = trim($message);
        if ($message === '') {
            throw new EmptyMessageException();
        }

        return $this;
    }

    /**
     * General method for sending message. Performs checks and send message
     */
    public function send(): void
    {
        $this->checkTargetIsNotNull();
        $this->checkBodyIsNotNull();
        $this->sendMessage();
    }

    /**
     * @return $this
     *
     * @throws \Inforisorse\SmsGateway\Exceptions\ApiClientLoginFailedException
     */
    protected function sendMessage(): self
    {
        $this->login();
        $payload = $this->makePayload();
        $this->sendSMS($payload);

        return $this;
    }

    /**
     * Pre-send checks: verify target(s) has been set
     *
     * @return $this
     */
    protected function checkTargetIsNotNull(): self
    {
        if (count($this->recipients) < 1) {
            throw new EmptyRecipientsException();
        }

        return $this;
    }

    /**
     * Pre-send check: verify message body is not empty
     *
     * @return $this
     */
    protected function checkBodyIsNotNull(): self
    {
        if (empty($this->message())) {
            throw new EmptyMessageException();
        }

        return $this;
    }

    /**
     * Compose the requested HTTP header line
     */
    protected function makeHeader(string $key, string $value): string
    {
        return sprintf('%s: %s', $key, $value);
    }

    /**
     * Set user:pass authentication for login
     *
     * @return $this
     */
    protected function setLoginAuth(): self
    {
        curl_setopt($this->curlHandle, CURLOPT_USERPWD, sprintf('%s:%s', $this->getUsername(), $this->getPassword()));

        return $this;
    }

    /**
     * Get API username from configuration
     */
    protected function getUsername(): string
    {
        return $this->getDriverParam('username');
    }

    /**
     * Get password for configuration
     */
    protected function getPassword(): string
    {
        return $this->getDriverParam('password');
    }

    /**
     * Descendants will provide login methos to initialize API authentication
     */
    abstract protected function login(): self;
}
