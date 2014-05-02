<?php

namespace LineStorm\PostBundle\Twig;

use LineStorm\PostBundle\Model\Post;
use LineStorm\Content\Component\ComponentInterface;
use LineStorm\PostBundle\Module\PostModule;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class PostExtension
 *
 * @package LineStorm\PostBundle\Twig
 */
class PostExtension extends \Twig_Extension
{
    /**
     * @var PostModule
     */
    private $postModule;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param PostModule $postModule
     * @param Container  $container
     */
    public function __construct(PostModule $postModule, Container $container)
    {
        $this->container  = $container;
        $this->postModule = $postModule;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_post_component_view', array($this, 'renderPostComponentView'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('cms_post_top_ten', array($this, 'getTopTen')),
            new \Twig_SimpleFunction('cms_post_related', array($this, 'getRelatedPosts')),
        );
    }

    /**
     * Get the top 10 posts
     *
     * @return Post[]
     */
    public function getTopTen()
    {
        $modelManager = $this->container->get('linestorm.cms.model_manager');

        return $modelManager->get('post')->findTopTen();
    }

    public function getRelatedPosts($post)
    {
        $modelManager = $this->container->get('linestorm.cms.model_manager');

        return $modelManager->get('post')->findRelated($post);
    }

    /**
     * fetch the html for components
     * TODO: this is a bit shit.
     *
     * @param $entities
     *
     * @return mixed
     */
    public function renderPostComponentView($entities)
    {
        foreach($this->postModule->getComponents() as $component)
        {
            /** @var $component ComponentInterface */
            if($component->isSupported($entities))
            {
                return $component->getView($entities);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'linestorm_cms_module_post_extension';
    }
}
