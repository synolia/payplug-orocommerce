<?php

namespace Payplug\Bundle\PaymentBundle\Constant;

class PayplugFailureConstant
{
    const PROCESSING_ERROR = 'processing_error';
    const CARD_DECLINED = 'card_declined';
    const INSUFFICIENT_FUNDS = 'insufficient_funds';
    const IS_3DS_DECLINED = '3ds_declined';
    const INCORRECT_NUMBER = 'incorrect_number';
    const FRAUD_SUSPECTED = 'fraud_suspected';
    const METHOD_UNSUPPORTED = 'method_unsupported';
    const CARD_SCHEME_MISMATCH = 'card_scheme_mismatch';
    const CARD_EXPIRATION = 'card_expiration_date_prior_to_last_installment_date';
    const ABORTED = 'aborted';
    const TIMEOUT = 'timeout';

    public static function getAll(): array
    {
        return [
            self::PROCESSING_ERROR,
            self::CARD_DECLINED,
            self::INSUFFICIENT_FUNDS,
            self::IS_3DS_DECLINED,
            self::INCORRECT_NUMBER,
            self::FRAUD_SUSPECTED,
            self::METHOD_UNSUPPORTED,
            self::CARD_SCHEME_MISMATCH,
            self::CARD_EXPIRATION,
            self::ABORTED,
            self::TIMEOUT
        ];
    }
}
