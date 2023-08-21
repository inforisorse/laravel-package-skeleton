<?php
declare(strict_types=1);

namespace Inforisorse\SmsGateway\Interfaces;

interface SmsDriverInterface
{
    /**
     * Set target phone numbers
     * @param array|string $recipients
     * @return self
     */
    public function to(array|string $recipients): self;

    /**
     * Set sender name or phone number
     *
     * @param string|null $sender
     * @return self
     */
    public function from(?string $sender): self;

    /**
     * Set message content
     *
     * @param string $message
     * @return self
     */
    public function message(string $message): self;

    /**
     * Implements the providers' specific operations for sending the message
     *
     * @return void
     */
    public function send(): void;

}
