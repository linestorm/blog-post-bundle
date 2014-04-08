<?php

namespace LineStorm\PostBundle\Module\Component;


class AbstractHeaderComponent extends AbstractComponent
{
    public function getType()
    {
        return $this::TYPE_HEADER;
    }
} 
