<?php

namespace LineStorm\BlogPostBundle\Module\Component;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;

/**
 * This must be implemented by all components
 *
 * Interface ComponentInterface
 * @package LineStorm\BlogPostBundle\Module\Component
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
     * Fetch the template for the view
     *
     * @param $entity
     *
     * @return string
     */
    public function getViewTemplate($entity);

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
     * @return string
     */
    public function getFormAssetTemplate();

    /**
     * Build the form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return mixed
     */
    public function buildForm(FormBuilderInterface $builder, array $options);
}
