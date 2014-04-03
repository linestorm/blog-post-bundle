<?php

namespace LineStorm\BlogPostBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('line_storm_blog_post');

        $rootNode
            ->isRequired()
            ->children()
                ->arrayNode('entity_classes')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('post')->defaultValue('LineStorm\BlogPostBundle\Entity\BlogPost')->end()
                        ->scalarNode('post_article')->defaultValue('LineStorm\BlogPostBundle\Entity\BlogPostArticle')->end()
                        ->scalarNode('post_gallery')->defaultValue('LineStorm\BlogPostBundle\Entity\BlogPostGallery')->end()
                        ->scalarNode('post_gallery_image')->defaultValue('LineStorm\BlogPostBundle\Entity\BlogPostGalleryImage')->end()
                        ->scalarNode('tag')->defaultValue('LineStorm\BlogPostBundle\Entity\BlogTag')->end()
                        ->scalarNode('category')->defaultValue('LineStorm\BlogPostBundle\Entity\BlogCategory')->end()
                        ->scalarNode('user')->defaultValue('FOS\UserBundle\Entity\User')->end()
                        ->scalarNode('user_group')->defaultValue('FOS\UserBundle\Entity\Group')->end()
                    ->end()
                ->end()
            ->end()
        ;
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
