<?php

namespace Payplug\Bundle\PaymentBundle\Integration;

use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;
use Payplug\Bundle\PaymentBundle\Entity\PayplugSettings;
use Payplug\Bundle\PaymentBundle\Form\Type\PayplugSettingsType;

class PayplugTransport implements TransportInterface
{
    /**
     * {@inheritdoc}
     */
    public function init(Transport $transportEntity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'payplug.settings.transport.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsFormType()
    {
        return PayplugSettingsType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsEntityFQCN()
    {
        return PayplugSettings::class;
    }
}
