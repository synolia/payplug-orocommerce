<?php

namespace Payplug\Bundle\PaymentBundle\Tests\Unit\Method\Provider;

use Oro\Bundle\PaymentBundle\Tests\Unit\Method\Provider\AbstractMethodProviderTest;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;
use Payplug\Bundle\PaymentBundle\Method\Config\Provider\PayplugConfigProviderInterface;
use Payplug\Bundle\PaymentBundle\Method\Factory\PayplugPaymentMethodFactoryInterface;
use Payplug\Bundle\PaymentBundle\Method\Provider\PayplugMethodProvider;

class PayplugMethodProviderTest extends AbstractMethodProviderTest
{
    protected function setUp(): void
    {
        $this->configProvider = $this->createMock(PayplugConfigProviderInterface::class);
        $this->factory = $this->createMock(PayplugPaymentMethodFactoryInterface::class);
        $this->paymentConfigClass = PayplugConfigInterface::class;
        $this->methodProvider = new PayplugMethodProvider($this->configProvider, $this->factory);
    }
}
