<?php

namespace LineStorm\PostBundle\Module\Component;

use LineStorm\PostBundle\Model\PostGallery;
use LineStorm\PostBundle\Module\Component\View\ComponentView;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class GalleryComponent
 * @package LineStorm\PostBundle\Module\Component
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
        return new ComponentView('LineStormPostBundle:Component:Gallery/view.html.twig');
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('galleries', 'collection', array(
                'type'      => 'linestorm_cms_form_post_gallery',
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
