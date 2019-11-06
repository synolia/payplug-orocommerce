<?php

namespace Payplug\Bundle\PaymentBundle\Method\Factory;

use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;
use Payplug\Bundle\PaymentBundle\Method\Payplug;
use Payplug\Bundle\PaymentBundle\Service\Gateway;

class PayplugPaymentMethodFactory implements PayplugPaymentMethodFactoryInterface
{
    /**
     * @var Gateway
     */
    private $gateway;

    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }


    /**
     * {@inheritdoc}
     */
    public function create(PayplugConfigInterface $config)
    {
        return new Payplug(
            $config,
            $this->gateway
        );
    }
}
