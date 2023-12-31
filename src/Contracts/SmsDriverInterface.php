<?php

declare(strict_types=1);

namespace Inforisorse\SmsGateway\Interfaces;

interface SmsDriverInterface
{
    /**
     * Set target phone numbers
     *
     * @param  array|string  $recipients message target(s)
     */
    public function to(array|string $recipients): self;

    /**
     * Set sender name or phone number
     *
     * @param  string|null  $sender the message sender
     */
    public function from(?string $sender): self;

    /**
     * Set message content
     *
     * @param  string  $message the message text
     */
    public function send(string $message): self;

    /**
     * Implements the driver's specific operations for sending the message
     */
    public function dispatch(): void;
}
