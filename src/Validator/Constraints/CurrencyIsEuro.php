<?php

namespace Payplug\Bundle\PaymentBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CurrencyIsEuro extends Constraint
{
    public $message = 'payplug.constraints.payment_rule.euro_currency';

    public function validatedBy()
    {
        return 'payplug.validator.payment_rule.currency_is_euro';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}