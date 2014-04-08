<?php

namespace LineStorm\PostBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use LineStorm\PostBundle\Form\DataTransformer\TagTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TagType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'name' => null
            ))
            ->setRequired(array(
                'tag_class',
                'em'
            ))
            ->setAllowedTypes(array(
                'em' => 'Doctrine\Common\Persistence\ObjectManager',
            ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager  = $options['em'];
        $dataClass      = $options['tag_class'];
        $transformer = new TagTransformer($entityManager, $dataClass);

        $builder->addModelTransformer($transformer);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var EntityManager $entityManager */
        $entityManager  = $options['em'];
        $dataClass      = $options['tag_class'];
        $name           = $options['name'];
        $tags           = array();

        if($name)
        {
            $tagArray = $entityManager->getRepository($dataClass)->createQueryBuilder('c')->getQuery()->getArrayResult();
            foreach($tagArray as $tagData)
            {
                $tags[] = $tagData['name'];
            }
        }

        $view->vars['attr']['data-options'] = implode(',', $tags);
    }


    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'tag';
    }
}
