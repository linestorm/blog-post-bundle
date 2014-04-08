<?php

namespace LineStorm\PostBundle\Module\Component;

/**
 * Class AbstractBodyComponent
 * @package LineStorm\PostBundle\Module\Component
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
