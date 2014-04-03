<?php

namespace LineStorm\BlogPostBundle\Module\Component;

use LineStorm\BlogBundle\Model\ModelManager;
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

    protected $templating;

    public function __construct(ModelManager $modelManager)
    {
        $this->modelManager = $modelManager;
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
}
