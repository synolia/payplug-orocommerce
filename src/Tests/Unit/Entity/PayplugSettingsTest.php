<?php

namespace Payplug\Bundle\PaymentBundle\Tests\Unit\Entity;

use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use Oro\Component\Testing\Unit\EntityTestCaseTrait;
use Oro\Component\Testing\Unit\EntityTrait;
use Payplug\Bundle\PaymentBundle\Constant\PayplugSettingsConstant;
use Payplug\Bundle\PaymentBundle\Entity\PayplugSettings;
use Symfony\Component\HttpFoundation\ParameterBag;

class PayplugSettingsTest extends \PHPUnit\Framework\TestCase
{
    use EntityTestCaseTrait;
    use EntityTrait;

    public function testAccessors()
    {
        static::assertPropertyAccessors(new PayplugSettings(), [
            ['login', 'some login'],
            ['debugMode', false],
            ['apiKeyTest', 'some test key'],
            ['apiKeyLive', 'some live key'],
            ['mode', PayplugSettingsConstant::MODE_TEST],
        ]);

        static::assertPropertyCollections(new PayplugSettings(), [
            ['labels', new LocalizedFallbackValue()],
            ['shortLabels', new LocalizedFallbackValue()],
        ]);
    }

    public function testGetSettingsBag()
    {
        /** @var PayplugSettings $entity */
        $entity = $this->getEntity(
            PayplugSettings::class,
            [
                'login' => 'some login',
                'debugMode' => false,
                'apiKeyTest' => 'some test key',
                'apiKeyLive' => 'some live key',
                'mode' => PayplugSettingsConstant::MODE_TEST,
                'labels' => [(new LocalizedFallbackValue())->setString('label')],
                'shortLabels' => [(new LocalizedFallbackValue())->setString('lbl')],
            ]
        );

        /** @var ParameterBag $result */
        $result = $entity->getSettingsBag();

        static::assertEquals('some login', $result->get('login'));
        static::assertEquals(false, $result->get('debugMode'));
        static::assertEquals('some test key', $result->get('apiKeyTest'));
        static::assertEquals('some live key', $result->get('apiKeyLive'));
        static::assertEquals(PayplugSettingsConstant::MODE_TEST, $result->get('mode'));

        static::assertEquals(
            $result->get('labels'),
            $entity->getLabels()
        );
        static::assertEquals(
            $result->get('short_labels'),
            $entity->getShortLabels()
        );
    }
}
