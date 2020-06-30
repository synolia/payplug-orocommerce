<?php

namespace Payplug\Bundle\PaymentBundle;

use Payplug\Bundle\PaymentBundle\DependencyInjection\OverrideCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PayplugPaymentBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new OverrideCompilerPass());
    }
}
