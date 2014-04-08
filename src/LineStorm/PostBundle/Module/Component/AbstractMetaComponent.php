<?php

namespace LineStorm\PostBundle\Module\Component;


class AbstractMetaComponent extends AbstractComponent
{
    public function getType()
    {
        return $this::TYPE_META;
    }
} 
