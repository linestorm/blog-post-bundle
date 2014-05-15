<?php

namespace LineStorm\PostBundle\Tests\Twig;

use LineStorm\PostBundle\Module\PostModule;
use LineStorm\PostBundle\Twig\PostExtension;

/**
 * Unit Tests for PostExtension
 *
 * Class PostExtensionTest
 *
 * @see PostExtension
 * @package LineStorm\PostBundle\Tests\Twig
 */
class PostExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Fetch the post extension
     *
     * @return PostExtension
     */
    protected function getExtentsion()
    {
        $module = new PostModule();
        $container = $this->getMock('\Symfony\Component\DependencyInjection\Container');
        return new PostExtension($module, $container);
    }


    public function testName()
    {
        $ext = $this->getExtentsion();

        $this->assertEquals('linestorm_cms_module_post_extension', $ext->getName());

    }


    public function  testFunctions()
    {
        $ext = $this->getExtentsion();

        $functions = $ext->getFunctions();

        $this->assertTrue(is_array($functions));

        foreach($functions as $function)
        {
            $this->assertInstanceOf('\Twig_SimpleFunction', $function);

        }
    }

}
