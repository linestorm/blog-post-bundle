<?php

namespace LineStorm\BlogPostBundle\Module\Component;

use LineStorm\BlogPostBundle\Model\PostGallery;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class GalleryComponent
 * @package LineStorm\BlogPostBundle\Module\Component
 */
class GalleryComponent extends AbstractBodyComponent implements ComponentInterface
{
    protected $name = 'Gallery';
    protected $id = 'galleries';

    /**
     * @inheritdoc
     */
    public function isSupported($entity)
    {
        return ($entity instanceof PostGallery);
    }


    /**
     * @inheritdoc
     */
    public function getViewTemplate($entity)
    {
        return 'LineStormBlogPostBundle:Component:Gallery/view.html.twig';
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('galleries', 'collection', array(
                'type'      => 'linestorm_blog_form_post_gallery',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'     => false,
            ))
        ;
    }

    /**
     * @inheritdoc
     */
    public function getRoutes(LoaderInterface $loader)
    {
        return null;
    }
}
