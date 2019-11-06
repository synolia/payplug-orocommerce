<?php

namespace Payplug\Bundle\PaymentBundle\Method;

use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Payplug\Bundle\PaymentBundle\Method\Config\PayplugConfigInterface;
use Payplug\Bundle\PaymentBundle\Service\Gateway;
use Payplug\Resource\Payment;
use Payplug\Resource\Refund;
use Payplug\Resource\Refund as ResourceRefund;

class Payplug implements PaymentMethodInterface
{
    public const REFUND = 'refund';

    /**
     * @var PayplugConfigInterface
     */
    private $config;

    /**
     * @var Gateway
     */
    private $gateway;

    public function __construct(PayplugConfigInterface $config, Gateway $gateway)
    {
        $this->config = $config;
        $this->gateway = $gateway;
    }


    /**
     * {@inheritdoc}
     */
    public function execute($action, PaymentTransaction $paymentTransaction)
    {
        if (!$this->supports($action)) {
            throw new \InvalidArgumentException(sprintf('Unsupported action "%s"', $action));
        }

        return $this->{$action}($paymentTransaction) ?: [];
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->config->getPaymentMethodIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicable(PaymentContextInterface $context)
    {
        return $this->config->isConnected();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($actionName)
    {
        return $actionName === self::PURCHASE;
    }

    public function isDebugMode(): bool
    {
        return $this->config->isDebugMode();
    }

    public function isConnected(): bool
    {
        return $this->config->isConnected();
    }

    public function getTransactionInfos(PaymentTransaction $paymentTransaction): Payment
    {
        return $this->gateway->getTransactionInfos($paymentTransaction->getReference(), $this->config);
    }

    public function refundPaymentTransaction(PaymentTransaction $paymentTransaction, float $amount): ?Refund
    {
        return $this->gateway->refundPayment($paymentTransaction, $this->config, $amount);
    }

    public function getMaximumRefundAmount(PaymentTransaction $paymentTransaction): float
    {
        return $this->gateway->getMaximumRefundAmount($paymentTransaction->getReference(), $this->config);
    }

    public function treatNotify()
    {
        return $this->gateway->treatNotify($this->config);
    }

    protected function purchase(PaymentTransaction $paymentTransaction): array
    {
        $payment = $this->gateway->createPayment($paymentTransaction, $this->config);

        $paymentTransaction->setReference($payment->id);

        return [
            'purchaseRedirectUrl' => $payment->hosted_payment->payment_url,
        ];
    }

    public function getRefundList(string $reference): array
    {
        return $this->gateway->getRefundList($reference, $this->config);
    }
}
