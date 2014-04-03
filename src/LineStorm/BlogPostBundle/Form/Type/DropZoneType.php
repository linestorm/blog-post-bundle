<?php

namespace LineStorm\BlogPostBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DropZoneType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label' => false,
        ));
    }

    public function getParent()
    {
        return 'collection';
    }

    public function getName()
    {
        return 'dropzone';
    }
}
