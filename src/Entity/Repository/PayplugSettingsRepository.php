<?php

namespace Payplug\Bundle\PaymentBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Payplug\Bundle\PaymentBundle\Entity\PayplugSettings;

class PayplugSettingsRepository extends EntityRepository
{
    /**
     * @return PayplugSettings[]
     */
    public function getEnabledSettings()
    {
        return $this->createQueryBuilder('settings')
            ->innerJoin('settings.channel', 'channel')
            ->andWhere('channel.enabled = true')
            ->getQuery()
            ->getResult();
    }
}
