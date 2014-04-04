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

        if (!$container->hasDefinition('linestorm.blog.model_manager')) {
            return;
        }

        $definition = $container->getDefinition('linestorm.blog.module.post');
        $modelManagerRef = new Reference('linestorm.blog.model_manager');
        $containerRef = new Reference('service_container');

        $taggedServices = $container->findTaggedServiceIds('linestorm.blog.module.post.component');

        foreach ($taggedServices as $id => $attributes) {
            $tagReference = new Reference($id);
            $definition->addMethodCall(
                'addComponent',
                array($tagReference)
            );

            $componentDefinition = $container->getDefinition($id);

            $componentDefinition->setArguments(array(
                $modelManagerRef, $containerRef
            ));
        }
    }
} 
