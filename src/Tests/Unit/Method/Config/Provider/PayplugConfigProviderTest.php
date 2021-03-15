<?php

namespace Payplug\Bundle\PaymentBundle\Tests\Unit\Method\Config\Provider;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\EntityBundle\ORM\Registry;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Component\Testing\Unit\EntityTrait;
use Payplug\Bundle\PaymentBundle\Entity\PayplugSettings;
use Payplug\Bundle\PaymentBundle\Entity\Repository\PayplugSettingsRepository;
use Payplug\Bundle\PaymentBundle\Method\Config\Factory\PayplugConfigFactory;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfig;
use Payplug\Bundle\PaymentBundle\Method\Config\Provider\PayplugConfigProvider;
use Psr\Log\LoggerInterface;

class PayplugConfigProviderTest extends \PHPUnit\Framework\TestCase
{
    use EntityTrait;

    /**
     * @var Registry|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $doctrine;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var PayplugSettings[]
     */
    protected $settings;

    /**
     * @var PayplugConfigProvider
     */
    protected $payplugConfigProvider;
    
    protected function setUp(): void
    {
        $this->type = 'payplug';

        $channel1 = $this->getEntity(Channel::class, ['id' => 1, 'type' => $this->type]);
        $channel2 = $this->getEntity(Channel::class, ['id' => 2, 'type' => $this->type]);

        $this->settings[] = $this->getEntity(PayplugSettings::class, ['id' => 1, 'channel' => $channel1]);
        $this->settings[] = $this->getEntity(PayplugSettings::class, ['id' => 2, 'channel' => $channel2]);

        $config = $this->createMock(PayplugConfig::class);
        $config->expects($this->atLeastOnce())
            ->method('getPaymentMethodIdentifier')
            ->willReturn('payplug_1');
        $config->expects($this->atLeastOnce())
            ->method('getPaymentMethodIdentifier')
            ->willReturn('payplug_2');

        $this->doctrine = $this->createMock(Registry::class);

        $objectRepository = $this->createMock(PayplugSettingsRepository::class);
        $objectRepository->expects(static::once())
            ->method('getEnabledSettings')
            ->willReturn($this->settings);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects(static::once())->method('getRepository')->willReturn($objectRepository);

        $this->doctrine->expects(static::once())->method('getManagerForClass')->willReturn($entityManager);

        /** @var PayplugConfigFactory|\PHPUnit\Framework\MockObject\MockObject $factory */
        $factory = $this->createMock(PayplugConfigFactory::class);
        $factory->expects(static::exactly(2))
            ->method('create')
            ->willReturn($config);

        /** @var LoggerInterface|\PHPUnit\Framework\MockObject\MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);

        $this->payplugConfigProvider = new PayplugConfigProvider(
            $this->doctrine,
            $logger,
            $factory
        );
    }

    public function testGetPaymentConfigs()
    {
        $this->assertCount(1, $this->payplugConfigProvider->getPaymentConfigs());
    }

    public function testGetPaymentConfig()
    {
        $this->assertInstanceOf(
            PayplugConfig::class,
            $this->payplugConfigProvider->getPaymentConfig('payplug_1')
        );
    }

    public function testHasPaymentConfig()
    {
        $this->assertTrue($this->payplugConfigProvider->hasPaymentConfig('payplug_1'));
    }
}
