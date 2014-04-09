<?php

namespace LineStorm\PostBundle\Module\Component;

use LineStorm\CmsBundle\Model\ModelManager;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormView;

/**
 * Class AbstractComponent
 * @package LineStorm\PostBundle\Module\Component
 */
abstract class AbstractComponent
{
    const TYPE_HEADER   = 1;
    const TYPE_META     = 2;
    const TYPE_BODY     = 3;
    const TYPE_FOOTER   = 4;

    protected $name;
    protected $id;

    /**
     * @var ModelManager
     */
    protected $modelManager;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param ModelManager $modelManager
     * @param Container    $container
     */
    public function __construct(ModelManager $modelManager, Container $container)
    {
        $this->modelManager = $modelManager;
        $this->container = $container;
    }

    /**
     * Fetch the component id string
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Fetch the component name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Build the edit/creation form type
     *
     * @param FormView $view
     *
     * @return mixed
     */
    public function getForm(FormView $view)
    {
        return $this->container->get('templating')->render('LineStormPostBundle:Component:form.html.twig', array(
            'form'          => $view,
            'component'     => $this,
        ));
    }

    /**
     * Returns a template that will render a list of assets to include in the head for this component when editing
     *
     * @return null|string
     */
    public function getFormAssetTemplate()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getViewAssetTemplate()
    {
        return null;
    }

    /**
     * Convert a string into a component type
     *
     * @param $type
     *
     * @return int|null
     */
    static function strToType($type)
    {
        $type = strtoupper($type);
        switch($type)
        {
            case 'HEADER':
                return self::TYPE_HEADER;
                break;
            case 'META':
                return self::TYPE_META;
                break;
            case 'BODY':
                return self::TYPE_BODY;
                break;
            case 'FOOTER':
                return self::TYPE_FOOTER;
                break;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getRoutes(Loader $loader)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getAdminRoutes(Loader $loader)
    {
        return null;
    }

}
