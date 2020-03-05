<?php

namespace Payplug\Bundle\PaymentBundle\Method\Config;

use Oro\Bundle\PaymentBundle\Method\Config\ParameterBag\AbstractParameterBagPaymentConfig;

class PayplugConfig extends AbstractParameterBagPaymentConfig implements PayplugConfigInterface
{
    const LOGIN = 'login';
    const API_KEY_TEST = 'api_key_test';
    const API_KEY_LIVE = 'api_key_live';
    const DEBUG_MODE = 'debug_mode';
    const MODE = 'mode';

    public function getLogin(): string
    {
        return (string) $this->get(self::LOGIN);
    }

    public function getApiKeyTest(): string
    {
        return (string) $this->get(self::API_KEY_TEST);
    }

    public function getApiKeyLive(): string
    {
        return (string) $this->get(self::API_KEY_LIVE);
    }

    public function isDebugMode(): bool
    {
        return (bool) $this->get(self::DEBUG_MODE);
    }

    public function getMode(): string
    {
        return (string) $this->get(self::MODE);
    }

    public function isConnected(): bool
    {
        return (bool) !empty($this->get(self::API_KEY_LIVE)) || !empty($this->get(self::API_KEY_TEST));
    }
}
