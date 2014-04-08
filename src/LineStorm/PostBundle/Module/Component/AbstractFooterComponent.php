<?php

namespace LineStorm\PostBundle\Module\Component;


class AbstractFooterComponent extends AbstractComponent
{
    public function getType()
    {
        return $this::TYPE_FOOTER;
    }
} 
