<?php

declare(strict_types=1);

namespace Inforisorse\SmsGateway\Interfaces;

interface SmsGatewayInterface
{
    /**
     * Set the SMS driver to use
     */
    public function via(string $driverName): self;

    /**
     * Set message body
     */
    public function send(string $message): self;

    /**
     * Set target(s)
     */
    public function to(array|string $recipients): self;

    /**
     * Dispatch te message
     */
    public function dispatch(): self;

    /**
     * Set message sender
     */
    public function from(string $sender): self;
}
