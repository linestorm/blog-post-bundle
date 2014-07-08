<?php

namespace LineStorm\PostBundle\Module;

use LineStorm\CmsBundle\Module\ModuleInterface;
use LineStorm\Content\Module\AbstractContentModule;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class PostModule
 * @package LineStorm\PostBundle\Module
 */
class PostModule extends AbstractContentModule implements ModuleInterface
{
    protected $name = 'Post';
    protected $id = 'post';

    /**
     * The default page size for each frontend page
     *
     * @return int
     */
    public function getPageSize()
    {
        return 20;
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
     * The route to load as 'home'
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

    public function getAssets()
    {
        return array(
            '@LineStormPostBundle/Resources/assets/sass/list.scss',
            '@LineStormPostBundle/Resources/assets/sass/post.scss',
        );
    }
} 
