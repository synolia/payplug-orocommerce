<?php

namespace Payplug\Bundle\PaymentBundle\Tests\Unit\Method;

use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfig;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;
use Payplug\Bundle\PaymentBundle\Method\Payplug;
use Payplug\Bundle\PaymentBundle\Service\Gateway;

class PayplugTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PayplugConfigInterface
     */
    private $config;

    /**
     * @var Gateway
     */
    private $gateway;

    /**
     * @var Payplug
     */
    private $method;

    protected function setUp(): void
    {
        $this->config = $this->createMock(PayplugConfigInterface::class);
        $this->gateway = $this->createMock(Gateway::class);

        $this->method = new Payplug($this->config, $this->gateway);
    }

    public function testGetIdentifier()
    {
        $identifier = 'id';

        $this->config->expects(static::once())
            ->method('getPaymentMethodIdentifier')
            ->willReturn($identifier);

        $this->assertEquals($identifier, $this->method->getIdentifier());
    }

    public function testSupports()
    {
        $this->assertTrue($this->method->supports(Payplug::PURCHASE));
    }

    public function testIsApplicable()
    {
        /** @var PaymentContextInterface|\PHPUnit_Framework_MockObject_MockObject $context */
        $context = $this->createMock(PaymentContextInterface::class);
        $this->assertFalse($this->method->isApplicable($context));

        $this->config->method('isConnected')
            ->willReturn(true);
        $this->assertTrue($this->method->isApplicable($context));
    }

    public function testIsDebugMode()
    {
        $this->assertFalse($this->method->isDebugMode());

        $this->config->method('isDebugMode')
            ->willReturn(true);
        $this->assertTrue($this->method->isDebugMode());
    }

    public function testIsConnected()
    {
        $this->assertFalse($this->method->isConnected());

        $this->config->method('isConnected')
            ->willReturn(true);
        $this->assertTrue($this->method->isConnected());
    }
}
