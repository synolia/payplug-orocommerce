<?php


namespace Payplug\Bundle\PaymentBundle\Method\Factory;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;

interface PayplugPaymentMethodFactoryInterface
{
    /**
     * @param PayplugConfigInterface $config
     * @return PaymentMethodInterface
     */
    public function create(PayplugConfigInterface $config);
}
