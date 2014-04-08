<?php

namespace LineStorm\PostBundle\DependencyInjection\ContainerBuilder;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ComponentCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('linestorm.cms.module.post')) {
            return;
        }

        if (!$container->hasDefinition('linestorm.cms.model_manager')) {
            return;
        }

        $definition = $container->getDefinition('linestorm.cms.module.post');
        $modelManagerRef = new Reference('linestorm.cms.model_manager');
        $containerRef = new Reference('service_container');

        $taggedServices = $container->findTaggedServiceIds('linestorm.cms.module.post.component');

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
