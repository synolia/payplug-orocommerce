<?php

namespace Payplug\Bundle\PaymentBundle\Method\Config\Provider;

use Payplug\Bundle\PaymentBundle\Entity\PayplugSettings;
use Payplug\Bundle\PaymentBundle\Entity\Repository\PayplugSettingsRepository;
use Payplug\Bundle\PaymentBundle\Method\Config\Factory\PayplugConfigFactoryInterface;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;
use Psr\Log\LoggerInterface;
use Oro\Bundle\EntityBundle\ORM\Registry;

class PayplugConfigProvider implements PayplugConfigProviderInterface
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var PayplugConfigFactoryInterface
     */
    protected $configFactory;

    /**
     * @var PayplugConfigInterface[]
     */
    protected $configs;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        Registry $doctrine,
        LoggerInterface $logger,
        PayplugConfigFactoryInterface $configFactory
    ) {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->configFactory = $configFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentConfigs()
    {
        $configs = [];

        $settings = $this->getEnabledIntegrationSettings();

        foreach ($settings as $setting) {
            $config = $this->configFactory->create($setting);

            $configs[$config->getPaymentMethodIdentifier()] = $config;
        }

        return $configs;
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentConfig($identifier)
    {
        $paymentConfigs = $this->getPaymentConfigs();

        if ([] === $paymentConfigs || false === array_key_exists($identifier, $paymentConfigs)) {
            return null;
        }

        return $paymentConfigs[$identifier];
    }

    /**
     * {@inheritDoc}
     */
    public function hasPaymentConfig($identifier)
    {
        return null !== $this->getPaymentConfig($identifier);
    }

    /**
     * @return PayplugSettings[]
     */
    protected function getEnabledIntegrationSettings()
    {
        try {
            /** @var PayplugSettingsRepository $repository */
            $repository = $this->doctrine
                ->getManagerForClass(PayplugSettings::class)
                ->getRepository(PayplugSettings::class);

            return $repository->getEnabledSettings();
        } catch (\UnexpectedValueException $e) {
            $this->logger->critical($e->getMessage());

            return [];
        }
    }
}
