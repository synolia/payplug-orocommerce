<?php

namespace Payplug\Bundle\PaymentBundle\Datagrid;

use Oro\Bundle\DataGridBundle\Datasource\ResultRecordInterface;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Payplug\Bundle\PaymentBundle\Method\Payplug;

class ActionPermissionProvider
{
    /**
     * @var PaymentMethodProviderInterface
     */
    protected $paymentMethodProvider;

    public function __construct(PaymentMethodProviderInterface $paymentMethodProvider)
    {
        $this->paymentMethodProvider = $paymentMethodProvider;
    }


    public function getActionPermissions(ResultRecordInterface $record): array
    {
        $paymentMethod = $this->paymentMethodProvider->getPaymentMethod($record->getValue('paymentMethod'));

        if (!$paymentMethod) {
            return [];
        }

        $displayInformationsButton = false;

        if ($paymentMethod instanceof Payplug
            && $paymentMethod->isConnected()
            && $record->getValue('action') == PaymentMethodInterface::PURCHASE
        ) {
            $displayInformationsButton = true;
        }

        return [
            'informations' => $displayInformationsButton
        ];
    }
}
