<?php

namespace LineStorm\BlogPostBundle\Module\Component;

use LineStorm\BlogBundle\Model\ModelManager;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormView;
use Symfony\Component\Templating\EngineInterface;

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
     * @var TwigEngine
     */
    protected $templating;

    /**
     * @var Container
     */
    protected $container;

    public function __construct(ModelManager $modelManager, Container $container)
    {
        $this->modelManager = $modelManager;
        $this->container = $container;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTemplateEngine(EngineInterface $templating)
    {
        $this->templating   = $templating;
    }

    public function getForm(FormView $view)
    {
        return $this->container->get('templating')->render('LineStormBlogBundle:Component:form.html.twig', array(
            'form'          => $view,
            'component'     => $this,
        ));
    }

    public function getFormAssetTemplate()
    {
        return null;
    }


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


    public function getFormTemplate()
    {
        return '';
    }
}
