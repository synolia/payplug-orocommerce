<?php

namespace Payplug\Bundle\PaymentBundle\Tests\Unit\Method\Factory;

use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;
use Payplug\Bundle\PaymentBundle\Method\Factory\PayplugPaymentMethodFactory;
use Payplug\Bundle\PaymentBundle\Method\Payplug;
use Payplug\Bundle\PaymentBundle\Service\Gateway;

class PayplugPaymentMethodFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Gateway
     */
    private $gateway;

    /**
     * @var PayplugPaymentMethodFactory
     */
    private $factory;

    protected function setUp(): void
    {
        $this->gateway = $this->createMock(Gateway::class);
        $this->factory = new PayplugPaymentMethodFactory($this->gateway);
    }

    public function testCreate()
    {
        /** @var PayplugConfigInterface $config */
        $config = $this->createMock(PayplugConfigInterface::class);

        $method = new Payplug($config, $this->gateway);

        static::assertEquals($method, $this->factory->create($config));
    }
}
