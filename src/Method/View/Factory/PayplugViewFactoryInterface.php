<?php

namespace Payplug\Bundle\PaymentBundle\Method\View\Factory;

use Oro\Bundle\PaymentBundle\Method\View\PaymentMethodViewInterface;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;

interface PayplugViewFactoryInterface
{
    /**
     * @param PayplugConfigInterface $config
     * @return PaymentMethodViewInterface
     */
    public function create(PayplugConfigInterface $config);
}
