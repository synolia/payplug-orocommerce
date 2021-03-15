<?php

namespace Payplug\Bundle\PaymentBundle\Tests\Unit\Method\View\Factory;

use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;
use Payplug\Bundle\PaymentBundle\Method\View\Factory\PayplugViewFactory;
use Payplug\Bundle\PaymentBundle\Method\View\PayplugView;

class PayplugViewFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PayplugViewFactory
     */
    private $factory;

    protected function setUp(): void
    {
        $this->factory = new PayplugViewFactory();
    }

    public function testCreate()
    {
        /** @var PayplugConfigInterface $config */
        $config = $this->createMock(PayplugConfigInterface::class);

        $expectedView = new PayplugView($config);

        $this->assertEquals($expectedView, $this->factory->create($config));
    }
}
