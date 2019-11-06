<?php

namespace Payplug\Bundle\PaymentBundle\Method\Config\Provider;

use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;

/**
 * Interface for config provider which allows to get configs based on payment method identifier
 */
interface PayplugConfigProviderInterface
{
    /**
     * @return PayplugConfigInterface[]
     */
    public function getPaymentConfigs();

    /**
     * @param string $identifier
     * @return PayplugConfigInterface|null
     */
    public function getPaymentConfig($identifier);

    /**
     * @param string $identifier
     * @return bool
     */
    public function hasPaymentConfig($identifier);
}
