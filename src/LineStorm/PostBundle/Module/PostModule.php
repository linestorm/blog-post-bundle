<?php

namespace LineStorm\PostBundle\Module;

use LineStorm\CmsBundle\Module\AbstractModule;
use LineStorm\CmsBundle\Module\ModuleInterface;
use LineStorm\PostBundle\Module\Component\AbstractComponent;
use LineStorm\PostBundle\Module\Component\ComponentInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class PostModule
 * @package LineStorm\PostBundle\Module
 */
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
     * @param null $type
     * @return Component\ComponentInterface[]
     */
    public function getComponents($type=null)
    {
        if($type === null)
        {
            return $this->components;
        }
        else
        {
            $components = array();
            foreach($this->components as $component)
            {
                if($component->getType() === AbstractComponent::strToType($type))
                {
                    $components[] = $component;
                }
            }

            return $components;
        }
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
            'View All' => array('linestorm_cms_module_post_admin_list', array()),
            'New' => array('linestorm_cms_module_post_admin_create', array()),
        );
    }

    /**
     * Thr route to load as 'home'
     *
     * @return string
     */
    public function getHome()
    {
        return 'linestorm_cms_admin_module_post';
    }

    /**
     * Add routes to the router
     * @param Loader $loader
     * @return RouteCollection
     */
    public function addRoutes(Loader $loader)
    {
        $moduleRoutes = $loader->import('@LineStormPostBundle/Resources/config/routing.yml', 'yaml');

        // import all the component routes
        foreach ($this->components as $component) {
            $routes = $component->getRoutes($loader);
            if ($routes instanceof RouteCollection) {
                $moduleRoutes->addCollection($routes);
            }
        }

        return $moduleRoutes;
    }

    /**
     * Add routes to the router
     * @param Loader $loader
     * @return RouteCollection
     */
    public function addAdminRoutes(Loader $loader)
    {
        $moduleRoutes = $loader->import('@LineStormPostBundle/Resources/config/routing/admin.yml', 'yaml');

        // import all the component routes
        foreach ($this->components as $component) {
            $routes = $component->getAdminRoutes($loader);
            if ($routes instanceof RouteCollection) {
                $moduleRoutes->addCollection($routes);
            }
        }

        return $moduleRoutes;
    }
} 
