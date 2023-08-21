<?php
declare(strict_types=1);


namespace Inforisorse\SmsGateway\Traits;

trait NamespaceUtils
{
    protected function getNamespace(): string
    {
        return __NAMESPACE__;
    }

    protected function cleanClassName(): string
    {
        return str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
    }
}
