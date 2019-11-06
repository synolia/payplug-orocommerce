<?php

namespace Payplug\Bundle\PaymentBundle\Method\View\Factory;

use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;
use Payplug\Bundle\PaymentBundle\Method\View\PayplugView;

class PayplugViewFactory implements PayplugViewFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(PayplugConfigInterface $config)
    {
        return new PayplugView($config);
    }
}
