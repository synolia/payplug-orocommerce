<?php

namespace Payplug\Bundle\PaymentBundle\Tests\Unit\Method\Config\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\IntegrationBundle\Generator\IntegrationIdentifierGeneratorInterface;
use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use Oro\Bundle\LocaleBundle\Helper\LocalizationHelper;
use Oro\Component\Testing\Unit\EntityTrait;
use Payplug\Bundle\PaymentBundle\Constant\PayplugSettingsConstant;
use Payplug\Bundle\PaymentBundle\Entity\PayplugSettings;
use Payplug\Bundle\PaymentBundle\Method\Config\Factory\PayplugConfigFactory;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfig;

class PayplugConfigFactoryTest extends \PHPUnit\Framework\TestCase
{
    use EntityTrait;

    /**
     * @var LocalizationHelper|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $localizationHelper;

    /**
     * @var IntegrationIdentifierGeneratorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $identifierGenerator;

    /**
     * @var PayplugConfigFactory
     */
    protected $payplugConfigFactory;

    protected function setUp(): void
    {
        $this->localizationHelper = $this->createMock(LocalizationHelper::class);
        $this->identifierGenerator = $this->createMock(IntegrationIdentifierGeneratorInterface::class);
        $this->payplugConfigFactory = new PayplugConfigFactory(
            $this->localizationHelper,
            $this->identifierGenerator
        );
    }

    public function testCreateConfig()
    {
        $label = (new LocalizedFallbackValue())->setString('test label');
        $labels = new ArrayCollection();
        $labels->add($label);

        $short_label = (new LocalizedFallbackValue())->setString('test short label');
        $short_labels = new ArrayCollection();
        $short_labels->add($short_label);

        /** @var Channel $channel */
        $channel = $this->getEntity(
            Channel::class,
            ['id' => 1]
        );

        $bag = [
            'channel' => $channel,
            'labels' => [new LocalizedFallbackValue()],
            'shortLabels' => [new LocalizedFallbackValue()],
            'login' => 'login',
            'debugMode' => true,
            'apiKeyTest' => 'some test key',
            'apiKeyLive' => 'some live key',
            'mode' => PayplugSettingsConstant::MODE_TEST
        ];
        /** @var PayplugSettings $payplugSettings */
        $payplugSettings = $this->getEntity(PayplugSettings::class, $bag);

        $this->localizationHelper->expects(static::exactly(2))
            ->method('getLocalizedValue')
            ->willReturnMap([
                [$payplugSettings->getLabels(), null, 'test label'],
                [$payplugSettings->getShortLabels(), null, 'test short label'],
            ]);

        $this->identifierGenerator->expects(static::once())
            ->method('generateIdentifier')
            ->with($channel)
            ->willReturn('payplug_1');

        $config = $this->payplugConfigFactory->create($payplugSettings);
        $expectedConfig = $this->getExpectedConfig();
        static::assertEquals($expectedConfig, $config);
    }

    /**
     * @return PayplugConfig
     */
    protected function getExpectedConfig()
    {
        $params = [
            'label' => 'test label',
            'short_label' => 'test short label',
            'admin_label' => null,
            'payment_method_identifier' => 'payplug_1',
            'login' => 'login',
            'debug_mode' => true,
            'api_key_test' => 'some test key',
            'api_key_live' => 'some live key',
            'mode' => PayplugSettingsConstant::MODE_TEST
        ];

        return new PayplugConfig($params);
    }
}
