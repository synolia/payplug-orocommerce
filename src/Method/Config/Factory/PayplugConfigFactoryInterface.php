<?php

namespace Payplug\Bundle\PaymentBundle\Method\Config\Factory;

use Payplug\Bundle\PaymentBundle\Entity\PayplugSettings;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;

interface PayplugConfigFactoryInterface
{
    /**
     * @param PayplugSettings $settings
     * @return PayplugConfigInterface
     */
    public function create(PayplugSettings $settings);
}
