<?php

namespace Payplug\Bundle\PaymentBundle\Controller;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PaymentTransactionController extends Controller
{

    /**
     * @Route("/info/{paymentTransactionId}/", name="payplug_payment_transaction_info")
     * @ParamConverter("paymentTransaction", class="OroPaymentBundle:PaymentTransaction", options={"id" = "paymentTransactionId"})
     * @Template
     */
    public function infoAction(PaymentTransaction $paymentTransaction)
    {
        $paymentMethod = $this->get('oro_payment.payment_method.composite_provider')->getPaymentMethod(
            $paymentTransaction->getPaymentMethod()
        );

        $payplugResponse = $paymentMethod->getTransactionInfos($paymentTransaction);

        return ['payplugResponse' => $payplugResponse];
    }
}
