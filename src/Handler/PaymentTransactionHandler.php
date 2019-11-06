<?php

namespace Payplug\Bundle\PaymentBundle\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Payplug\Bundle\PaymentBundle\Entity\PayplugSettings;
use Payplug\Bundle\PaymentBundle\Method\Payplug;
use Payplug\Bundle\PaymentBundle\Service\RefundManager;
use Symfony\Component\Form\FormInterface;

class PaymentTransactionHandler
{
    /**
     * @var PaymentMethodProviderInterface
     */
    protected $paymentMethodProvider;

    /**
     * @var RefundManager
     */
    protected $refundManager;

    public function __construct(
        PaymentMethodProviderInterface $paymentMethodProvider,
        RefundManager $refundManager
    ) {
        $this->paymentMethodProvider = $paymentMethodProvider;
        $this->refundManager = $refundManager;
    }


    public function refund(PaymentTransaction $paymentTransaction, FormInterface $form)
    {
        if (empty($paymentTransaction->getReference())) {
            return new \Exception('Payment reference is empty');
        }

        $payplugAmount = $form->get('payplugAmount')->getData();

        $paymentMethod = $this->paymentMethodProvider->getPaymentMethod($paymentTransaction->getPaymentMethod());
        $payplugRefund = $paymentMethod->refundPaymentTransaction($paymentTransaction, $payplugAmount);

        $this->refundManager->createRefundTransaction($paymentTransaction, $payplugRefund);
    }
}
