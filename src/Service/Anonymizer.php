<?php

namespace Payplug\Bundle\PaymentBundle\Service;

class Anonymizer
{
    const ANONYMIZER_KEYS = [
        'email',
        'first_name',
        'last_name',
        'address1',
        'address2',
        'city',
        'postcode',
    ];

    public function anonymizeArray(array $data): void
    {
        array_walk_recursive($data, array($this, '_anonymizeRecursive'));
    }

    private function _anonymizeRecursive(&$item = null, $key = null)
    {
        if (in_array($key, self::ANONYMIZER_KEYS)) {
            $item = str_pad(substr($item, 0, 1), strlen($item) - 1, '*') . substr($item, -1);
        }
    }
}
