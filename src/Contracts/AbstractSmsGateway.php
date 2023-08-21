<?php
declare(strict_types=1);

namespace Inforisorse\SmsGateway\Contracts;

use Inforisorse\SmsGateway\Interfaces\SmsGatewayInterface;

abstract class AbstractSmsGateway implements SmsGatewayInterface
{
    /**
     * Configuration root node
     */
    const CONFIG_NODE_ROOT = 'smsgateway';
    /**
     * Configuration drivers node
     */
    const CONFIG_NODE_DRIVERS = 'drivers';
    /**
     * Configuration defaults node
     */
    const CONFIG_NODE_DEFAULT = 'default';
    const CONFIG_DRIVER = 'driver';
    const CONFIG_CLASS = 'class';

    static public function smsDriversConfigPath(): string
    {
        return sprintf ('%s.%s', self::CONFIG_NODE_ROOT, self::CONFIG_NODE_DRIVERS);
    }

    /**
     * @inheritDoc
     */
    abstract public function via(string $driverName): SmsGatewayInterface;

    /**
     * @inheritDoc
     */
    abstract public function send(string $message): SmsGatewayInterface;

    /**
     * @inheritDoc
     */
    abstract public function to(array|string $recipients): SmsGatewayInterface;

    /**
     * @inheritDoc
     */
    abstract public function dispatch(): SmsGatewayInterface;

    /**
     * @inheritDoc
     */
    abstract public function from(string $sender): SmsGatewayInterface;
}
