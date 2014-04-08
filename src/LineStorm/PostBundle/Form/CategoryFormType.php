<?php

namespace LineStorm\PostBundle\Form;

use LineStorm\CmsBundle\Form\AbstractBlogFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryFormType extends AbstractBlogFormType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->modelManager->getEntityClass('category')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'linestorm_blog_form_post_category';
    }
}
