<?php

namespace LineStorm\BlogPostBundle\Module\Component;


class AbstractMetaComponent extends AbstractComponent
{
    public function getType()
    {
        return $this::TYPE_META;
    }
} 
