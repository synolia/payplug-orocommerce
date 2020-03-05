<?php

namespace Payplug\Bundle\PaymentBundle\Method\Provider;

use Oro\Bundle\PaymentBundle\Method\Provider\AbstractPaymentMethodProvider;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;
use Payplug\Bundle\PaymentBundle\Method\Config\Provider\PayplugConfigProviderInterface;
use Payplug\Bundle\PaymentBundle\Method\Factory\PayplugPaymentMethodFactoryInterface;

class PayplugMethodProvider extends AbstractPaymentMethodProvider
{
    /**
     * @var PayplugPaymentMethodFactoryInterface
     */
    protected $factory;

    /**
     * @var PayplugConfigProviderInterface
     */
    private $configProvider;

    /**
     * @param PayplugConfigProviderInterface $configProvider
     * @param PayplugPaymentMethodFactoryInterface $factory
     */
    public function __construct(
        PayplugConfigProviderInterface $configProvider,
        PayplugPaymentMethodFactoryInterface $factory
    ) {
        parent::__construct();

        $this->configProvider = $configProvider;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    protected function collectMethods()
    {
        $configs = $this->configProvider->getPaymentConfigs();
        foreach ($configs as $config) {
            $this->addPayplugMethod($config);
        }
    }

    /**
     * @param PayplugConfigInterface $config
     */
    protected function addPayplugMethod(PayplugConfigInterface $config)
    {
        $this->addMethod(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
