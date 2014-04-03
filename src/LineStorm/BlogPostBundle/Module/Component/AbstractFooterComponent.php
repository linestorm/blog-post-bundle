<?php

namespace LineStorm\BlogPostBundle\Module\Component;


class AbstractFooterComponent extends AbstractComponent
{
    public function getType()
    {
        return $this::TYPE_FOOTER;
    }
} 
