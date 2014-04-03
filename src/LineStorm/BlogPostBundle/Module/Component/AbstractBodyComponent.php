<?php

namespace LineStorm\BlogPostBundle\Module\Component;


class AbstractBodyComponent extends AbstractComponent
{
    public function getType()
    {
        return $this::TYPE_BODY;
    }
} 
