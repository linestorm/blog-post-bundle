<?php

namespace LineStorm\PostBundle\Form;

use LineStorm\CmsBundle\Form\AbstractBlogFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostGalleryFormType extends AbstractBlogFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('images', 'dropzone', array(
                'type'      => 'linestorm_blog_form_post_gallery_image',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'prototype_name' => '__img_name__'
            ))
            ->add('body', 'textarea', array(
                'attr' => array(
                    'class' => 'ckeditor-textarea gallery-body',
                ),
                'label' => false,
                //'inline' => true,
            ))
            ->add('order', 'hidden')

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->modelManager->getEntityClass('post_gallery')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'linestorm_blog_form_post_gallery';
    }
}
