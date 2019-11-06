<?php

namespace Payplug\Bundle\PaymentBundle\Method\View\Provider;

use Oro\Bundle\PaymentBundle\Method\View\AbstractPaymentMethodViewProvider;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;
use Payplug\Bundle\PaymentBundle\Method\Config\Provider\PayplugConfigProviderInterface;
use Payplug\Bundle\PaymentBundle\Method\View\Factory\PayplugViewFactoryInterface;

class PayplugViewProvider extends AbstractPaymentMethodViewProvider
{
    /** @var PayplugViewFactoryInterface */
    private $factory;

    /** @var PayplugConfigProviderInterface */
    private $configProvider;

    /**
     * @param PayplugConfigProviderInterface $configProvider
     * @param PayplugViewFactoryInterface $factory
     */
    public function __construct(
        PayplugConfigProviderInterface $configProvider,
        PayplugViewFactoryInterface $factory
    ) {
        $this->factory = $factory;
        $this->configProvider = $configProvider;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function buildViews()
    {
        $configs = $this->configProvider->getPaymentConfigs();
        foreach ($configs as $config) {
            $this->addPayplugView($config);
        }
    }

    /**
     * @param PayplugConfigInterface $config
     */
    protected function addPayplugView(PayplugConfigInterface $config)
    {
        $this->addView(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
