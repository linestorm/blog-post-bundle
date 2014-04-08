<?php

namespace LineStorm\PostBundle\Module\Component\View;

/**
 * Class ComponentView
 * @package LineStorm\PostBundle\Module\Component\View
 */
class ComponentView
{
    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $template
     * @param array $options
     */
    function __construct($template, array $options = array())
    {
        $this->options  = $options;
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }


} 
