<?php

namespace LineStorm\BlogPostBundle\Module;

use LineStorm\BlogBundle\Module\AbstractModule;
use LineStorm\BlogBundle\Module\ModuleInterface;
use LineStorm\BlogPostBundle\Module\Component\ComponentInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\RouteCollection;

class PostModule extends AbstractModule implements ModuleInterface
{
    protected $name = 'Post';
    protected $id = 'post';

    /**
     * @var ComponentInterface[]
     */
    private $components = array();

    /**
     * @param $componentId
     * @return $this
     */
    public function removeComponent($componentId)
    {
        unset($this->components[$componentId]);

        return $this;
    }

    /**
     * @param $componentId
     * @return ComponentInterface
     */
    public function getComponent($componentId)
    {
        if (array_key_exists($componentId, $this->components))
            return $this->components[$componentId];
        else
            return null;
    }

    /**
     * @return Component\ComponentInterface[]
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * @param array $components
     * @return $this
     */
    public function setComponents(array $components)
    {
        foreach ($components as $component) {
            $this->addComponent($component);
        }

        return $this;
    }

    /**
     * @param ComponentInterface $component
     * @return $this
     */
    public function addComponent(ComponentInterface $component)
    {
        $this->components[$component->getId()] = $component;

        return $this;
    }

    /**
     * Returns the navigation array
     *
     * @return array
     */
    public function getNavigation()
    {
        return array(
            'View All' => array('linestorm_blog_post_module_admin_list', array()),
            'New' => array('linestorm_blog_post_module_admin_create', array()),
        );
    }

    /**
     * Thr route to load as 'home'
     *
     * @return string
     */
    public function getHome()
    {
        return 'linestorm_blog_admin_module_post';
    }

    /**
     * Add routes to the router
     * @param LoaderInterface $loader
     * @return RouteCollection
     */
    public function addRoutes(LoaderInterface $loader)
    {
        $moduleRoutes = $loader->import('@LineStormBlogPostBundle/Resources/config/routing.yml', 'yaml');

        // import all the component routes
        foreach ($this->components as $component) {
            $routes = $component->getRoutes($loader);
            if ($routes instanceof RouteCollection) {
                $moduleRoutes->addCollection($routes);
            }
        }

        return $moduleRoutes;
    }
} 
