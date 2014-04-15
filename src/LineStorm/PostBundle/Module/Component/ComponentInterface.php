<?php

namespace LineStorm\PostBundle\Module\Component;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\RouteCollection;

/**
 * This must be implemented by all components
 *
 * Interface ComponentInterface
 * @package LineStorm\PostBundle\Module\Component
 */
interface ComponentInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return integer
     */
    public function getType();

    /**
     * Get an array of any assets needed to build the admin page
     *
     * @return array
     */
    public function getAssets();

    /**
     * Fetch the template for the view
     *
     * @param $entity
     *
     * @return string
     */
    public function getView($entity);

    /**
     * Get a rendered include of assets
     *
     * @return mixed
     */
    public function getViewAssets();

    /**
     * Check if the entity is supported by this component
     *
     * @param $entity
     *
     * @return boolean
     */
    public function isSupported($entity);

    /**
     * Get a compiled form for this component
     *
     * @param FormView $view
     *
     * @return string
     */
    public function getForm(FormView $view);

    /**
     * Build the form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return mixed
     */
    public function buildForm(FormBuilderInterface $builder, array $options);

    /**
     * Add routes to the cms router.
     * Normally, for a component, there are no routes. Exceptions apply, for example Tags, where you want the user to be
     * able to view all tags.
     *
     * @param Loader $loader
     * @return RouteCollection
     */
    public function getRoutes(Loader $loader);

    /**
     * Add routes to the admin router.
     * Normally, for a component, there are no routes. Exceptions apply, for example Tags, where you want the user to be
     * able to view all tags.
     *
     * @param Loader $loader
     * @return RouteCollection
     */
    public function getAdminRoutes(Loader $loader);

}
