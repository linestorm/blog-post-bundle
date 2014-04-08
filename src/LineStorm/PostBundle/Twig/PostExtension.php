<?php

namespace LineStorm\PostBundle\Twig;

use LineStorm\CmsBundle\Module\ModuleManager;
use LineStorm\PostBundle\Module\Component\ComponentInterface;
use LineStorm\PostBundle\Module\PostModule;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class PostExtension
 * @package LineStorm\PostBundle\Twig
 */
class PostExtension extends \Twig_Extension
{
    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @var PostModule
     */
    private $postModule;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param ModuleManager $moduleManager
     * @param Container     $container
     */
    public function __construct(ModuleManager $moduleManager, Container $container)
    {
        $this->container = $container;
        $this->moduleManager = $moduleManager;
        $this->postModule = $this->moduleManager->getModule('post');
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_post_component_view', array($this, 'renderPostComponentView'), array('is_safe' => array('html'))),
        );
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
                return $component->getViewTemplate($entities);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'linestorm_blog_module_post_extension';
    }
}
