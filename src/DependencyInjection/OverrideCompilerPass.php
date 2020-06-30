<?php

namespace Payplug\Bundle\PaymentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('oro_currency.formatter.money_value_type')) {
            $definition = $container->getDefinition('oro_currency.formatter.money_value_type');
            $definition->setPublic(true);
        }
    }
}
