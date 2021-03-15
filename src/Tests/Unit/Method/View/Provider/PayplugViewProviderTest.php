<?php

namespace Payplug\Bundle\PaymentBundle\Tests\Unit\Method\View\Provider;

use Oro\Bundle\PaymentBundle\Tests\Unit\Method\View\Provider\AbstractMethodViewProviderTest;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;
use Payplug\Bundle\PaymentBundle\Method\Config\Provider\PayplugConfigProviderInterface;
use Payplug\Bundle\PaymentBundle\Method\View\Factory\PayplugViewFactoryInterface;
use Payplug\Bundle\PaymentBundle\Method\View\Provider\PayplugViewProvider;

class PayplugViewProviderTest extends AbstractMethodViewProviderTest
{
    protected function setUp(): void
    {
        $this->factory = $this->createMock(PayplugViewFactoryInterface::class);
        $this->configProvider = $this->createMock(PayplugConfigProviderInterface::class);
        $this->paymentConfigClass = PayplugConfigInterface::class;
        $this->provider = new PayplugViewProvider($this->configProvider, $this->factory);
    }
}
