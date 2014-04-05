<?php

namespace LineStorm\BlogPostBundle\Module\Component;

/**
 * Class AbstractBodyComponent
 * @package LineStorm\BlogPostBundle\Module\Component
 */
class AbstractBodyComponent extends AbstractComponent
{
    /**
     * Returns the body type
     *
     * @return mixed
     */
    public function getType()
    {
        return $this::TYPE_BODY;
    }
} 
