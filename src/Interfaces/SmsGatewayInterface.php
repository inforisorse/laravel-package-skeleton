<?php
declare(strict_types=1);


namespace Inforisorse\SmsGateway\Interfaces;

interface SmsGatewayInterface
{

    /**
     * Set the SMS driver to use
     *
     * @param string $driverName
     * @return self
     */
    public function via(string $driverName): self;

    /**
     * Set message body
     *
     * @param string $message
     * @return self
     */
    public function send(string $message): self;

    /**
     * Set target(s)
     *
     * @param array|string $recipients
     * @return self
     */
    public function to(array|string $recipients): self;

    /**
     * Dispatch te message
     * @return self
     */
    public function dispatch(): self;

    /**
     * Set message sender
     *
     * @param string $sender
     * @return self
     */
    public function from(string $sender): self;

}
