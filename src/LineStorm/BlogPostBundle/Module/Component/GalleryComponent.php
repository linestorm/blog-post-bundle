<?php

namespace LineStorm\BlogPostBundle\Module\Component;

use LineStorm\BlogPostBundle\Form\BlogPostGalleryType;
use LineStorm\BlogPostBundle\Model\Post;
use LineStorm\BlogPostBundle\Model\PostGallery;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;

class GalleryComponent extends AbstractBodyComponent implements ComponentInterface
{
    protected $name = 'Gallery';
    protected $id = 'galleries';

    public function isSupported($entity)
    {
        return ($entity instanceof PostGallery);
    }

    /**
     * Get the view html
     *
     * @param $entity PostGallery
     * @return string
     */
    public function getViewTemplate($entity)
    {
        return $this->templating->render('LineStormBlogBundle:Modules:Post/Component/gallery/view.html.twig', array(
            'gallery' => $entity,
        ));
    }

    /**
     * Get the new form html
     *
     * @return string
     */
    public function getNewTemplate()
    {
        return $this->templating->render('LineStormBlogBundle:Modules:Post/Component/gallery/new.html.twig');
    }

    /**
     * Get the edit html
     *
     * @param $entity PostGallery
     * @return string
     */
    public function getEditTemplate($entity)
    {
        return $this->templating->render('LineStormBlogBundle:Modules:Post/Component/gallery/new.html.twig');
    }

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

    public function createEntity(array $data)
    {
        $class  = $this->modelManager->getEntityClass('post_gallery');
        $entity = new $class();

        return $entity;
    }

    public function getRoutes(LoaderInterface $loader)
    {
        return null;
    }

    public function handleSave(Post $post, array $data)
    {
        // TODO: Implement handleSave() method.
    }
}
