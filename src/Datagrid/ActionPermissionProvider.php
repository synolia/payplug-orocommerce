<?php

namespace Payplug\Bundle\PaymentBundle\Datagrid;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\DataGridBundle\Datasource\ResultRecordInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Payplug\Bundle\PaymentBundle\Method\Payplug;

class ActionPermissionProvider
{
    /**
     * @var PaymentMethodProviderInterface
     */
    protected $paymentMethodProvider;

    /**
     * @var EntityManager
     */
    protected $manager;

    public function __construct(PaymentMethodProviderInterface $paymentMethodProvider, EntityManager $manager)
    {
        $this->paymentMethodProvider = $paymentMethodProvider;
        $this->manager = $manager;
    }

    public function getActionPermissions(ResultRecordInterface $record): array
    {
        $currentTransaction = $this->manager->getRepository(PaymentTransaction::class)->find($record->getValue('id'));
        $paymentMethod = $this->paymentMethodProvider->getPaymentMethod($currentTransaction->getPaymentMethod());

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
