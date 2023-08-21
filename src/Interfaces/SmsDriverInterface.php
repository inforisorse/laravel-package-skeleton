<?php

declare(strict_types=1);

namespace Inforisorse\SmsGateway\Interfaces;

interface SmsDriverInterface
{
    /**
     * Set target phone numbers
     */
    public function to(array|string $recipients): self;

    /**
     * Set sender name or phone number
     */
    public function from(?string $sender): self;

    /**
     * Set message content
     */
    public function message(string $message): self;

    /**
     * Implements the providers' specific operations for sending the message
     */
    public function send(): void;
}
