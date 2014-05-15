<?php

namespace LineStorm\PostBundle\Tests\Module;

use LineStorm\CmsBundle\Module\ModuleInterface;
use LineStorm\CmsBundle\Tests\Module\AbstractModuleTest;
use LineStorm\PostBundle\Module\PostModule;

/**
 * Unit Tests for Post Module
 *
 * Class PostModuleTest
 *
 * @see PostModule
 * @package LineStorm\PostBundle\Tests\Module
 */
class PostModuleTest extends AbstractModuleTest
{
    /**
     * @inheritdoc
     */
    protected function getModuleId()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    protected function getModuleName()
    {
        return 'Post';
    }

    /**
     * @inheritdoc
     */
    protected function getModule()
    {
        return new PostModule();
    }

    public function testModuleContent()
    {
        $module = $this->getModule();

        $this->assertInstanceOf('\LineStorm\Content\Module\AbstractContentModule', $module);
    }
}
