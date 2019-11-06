<?php

namespace Payplug\Bundle\PaymentBundle\Method\Config\Factory;

use Doctrine\Common\Collections\Collection;
use Oro\Bundle\IntegrationBundle\Generator\IntegrationIdentifierGeneratorInterface;
use Oro\Bundle\LocaleBundle\Helper\LocalizationHelper;
use Payplug\Bundle\PaymentBundle\Entity\PayplugSettings;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfig;

class PayplugConfigFactory implements PayplugConfigFactoryInterface
{
    /**
     * @var LocalizationHelper
     */
    private $localizationHelper;

    /**
     * @var IntegrationIdentifierGeneratorInterface
     */
    private $identifierGenerator;

    /**
     * @param LocalizationHelper $localizationHelper
     * @param IntegrationIdentifierGeneratorInterface $identifierGenerator
     */
    public function __construct(
        LocalizationHelper $localizationHelper,
        IntegrationIdentifierGeneratorInterface $identifierGenerator
    ) {
        $this->localizationHelper = $localizationHelper;
        $this->identifierGenerator = $identifierGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function create(PayplugSettings $settings)
    {
        $params = [];
        $channel = $settings->getChannel();

        $params[PayplugConfig::FIELD_LABEL] = $this->getLocalizedValue($settings->getLabels());
        $params[PayplugConfig::FIELD_SHORT_LABEL] = $this->getLocalizedValue($settings->getShortLabels());
        $params[PayplugConfig::FIELD_ADMIN_LABEL] = $channel->getName();
        $params[PayplugConfig::FIELD_PAYMENT_METHOD_IDENTIFIER] =
            $this->identifierGenerator->generateIdentifier($channel);

        $params[PayplugConfig::LOGIN] = $settings->getLogin();
        $params[PayplugConfig::API_KEY_TEST] = $settings->getApiKeyTest();
        $params[PayplugConfig::API_KEY_LIVE] = $settings->getApiKeyLive();
        $params[PayplugConfig::DEBUG_MODE] = $settings->isDebugMode();
        $params[PayplugConfig::MODE] = $settings->getMode();

        return new PayplugConfig($params);
    }

    /**
     * @param Collection $values
     *
     * @return string
     */
    private function getLocalizedValue(Collection $values)
    {
        return (string)$this->localizationHelper->getLocalizedValue($values);
    }
}
