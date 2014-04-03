<?php

namespace LineStorm\BlogPostBundle\DependencyInjection\ContainerBuilder;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ComponentCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('linestorm.blog.module.post')) {
            return;
        }

        $definition = $container->getDefinition(
            'linestorm.blog.module.post'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'linestorm.blog.module.post.component'
        );

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addComponent',
                array(new Reference($id))
            );
        }
    }
} 
