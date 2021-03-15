<?php

namespace Payplug\Bundle\PaymentBundle\Handler;

use Oro\Bundle\EntityBundle\ORM\Registry;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
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


    public function refund(PaymentTransaction $paymentTransaction, FormInterface $form): bool
    {
        if (empty($paymentTransaction->getReference())) {
            throw new \Exception('Payment reference is empty');
        }

        $payplugAmount = $form->get('payplugAmount')->getData();

        $paymentMethod = $this->paymentMethodProvider->getPaymentMethod($paymentTransaction->getPaymentMethod());
        $payplugRefund = $paymentMethod->refundPaymentTransaction($paymentTransaction, $payplugAmount);

        $this->refundManager->createRefundTransaction($paymentTransaction, $payplugRefund);

        return true;
    }
}
