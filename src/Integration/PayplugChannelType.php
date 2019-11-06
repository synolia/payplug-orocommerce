<?php

namespace Payplug\Bundle\PaymentBundle\Integration;

use Oro\Bundle\IntegrationBundle\Provider\ChannelInterface;
use Oro\Bundle\IntegrationBundle\Provider\IconAwareIntegrationInterface;

class PayplugChannelType implements ChannelInterface, IconAwareIntegrationInterface
{
    const TYPE = 'payplug';

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'payplug.channel_type.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return 'bundles/payplugpayment/img/icon_payplug.png';
    }
}
