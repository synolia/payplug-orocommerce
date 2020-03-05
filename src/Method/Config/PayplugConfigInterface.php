<?php

namespace Payplug\Bundle\PaymentBundle\Method\Config;

use Oro\Bundle\PaymentBundle\Method\Config\PaymentConfigInterface;

interface PayplugConfigInterface extends PaymentConfigInterface
{
    public function getLogin(): string;

    public function getApiKeyTest(): string;

    public function getApiKeyLive(): string;

    public function isDebugMode(): bool;

    public function getMode(): string;

    public function isConnected(): bool;
}
