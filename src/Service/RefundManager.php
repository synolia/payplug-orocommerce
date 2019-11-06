<?php

namespace Payplug\Bundle\PaymentBundle\Service;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Payplug\Bundle\PaymentBundle\Method\Payplug;
use Payplug\Resource\Refund;

class RefundManager
{
    /**
     * @var EntityManager
     */
    protected $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function createRefundTransaction(PaymentTransaction $paymentTransaction, Refund $payplugRefund)
    {
        $existingTransaction = $this->manager->getRepository(PaymentTransaction::class)->findOneBy([
            'reference' =>  $payplugRefund->id
        ]);

        if ($existingTransaction) {
            return;
        }

        $payplugRefundTransaction = new PaymentTransaction();
        $payplugRefundTransaction
            ->setActive(false)
            ->setSuccessful(true)
            ->setSourcePaymentTransaction($paymentTransaction)
            ->setReference($payplugRefund->id)
            ->setAmount((float) ($payplugRefund->amount / 100))
            ->setCurrency($payplugRefund->currency)
            ->setCreatedAt((new \DateTime())->setTimestamp($payplugRefund->created_at))
            ->setPaymentMethod($paymentTransaction->getPaymentMethod())
            ->setAction(Payplug::REFUND)
            ->setEntityClass($paymentTransaction->getEntityClass())
            ->setEntityIdentifier($paymentTransaction->getEntityIdentifier())
            ->setOrganization($paymentTransaction->getOrganization())
            ->setOwner($paymentTransaction->getOwner())
        ;

        $this->manager->persist($payplugRefundTransaction);
        $this->manager->flush();
    }
}
