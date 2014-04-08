<?php

namespace LineStorm\PostBundle\Form;

use LineStorm\CmsBundle\Form\AbstractCmsFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostGalleryImageFormType extends AbstractCmsFormType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', 'textarea', array(
                'attr' => array(
                    'style' => 'height:200px;'
                ),
            ))
            ->add('image' , 'linestorm_cms_form_media')
            ->add('order', 'hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label' => false,
            'data_class' => $this->modelManager->getEntityClass('post_gallery_image')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'linestorm_cms_form_post_gallery_image';
    }
}
