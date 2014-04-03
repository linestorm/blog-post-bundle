<?php

namespace LineStorm\BlogPostBundle\DependencyInjection\ContainerBuilder;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ComponentCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('linestorm.blog.module_manager')) {
            return;
        }

        $definition = $container->getDefinition(
            'linestorm.blog.module_manager'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'linestorm.blog.module'
        );

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addModule',
                array(new Reference($id))
            );
        }
    }
} 
