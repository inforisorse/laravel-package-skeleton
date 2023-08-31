<?php
declare(strict_types=1);

namespace Inforisorse\SmsGateway\Interfaces;

interface SmsDriverInterface
{
    /**
     * Set target phone numbers
     * @param array|string $recipients message target(s)
     * @return self
     */
    public function to(array|string $recipients): self;

    /**
     * Set sender name or phone number
     *
     * @param string|null $sender the message sender
     * @return self
     */
    public function from(?string $sender): self;

    /**
     * Set message content
     *
     * @param string $message the message text
     * @return self
     */
    public function send(string $message): self;

    /**
     * Implements the driver's specific operations for sending the message
     *
     * @return void
     */
    public function dispatch(): void;

}
