<?php

namespace LineStorm\PostBundle\Module\Component;

use LineStorm\PostBundle\Model\Tag;
use LineStorm\PostBundle\Module\Component\View\ComponentView;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TagComponent
 * @package LineStorm\PostBundle\Module\Component
 */
class TagComponent extends AbstractMetaComponent implements ComponentInterface
{
    protected $name = 'Tag';
    protected $id = 'tags';

    /**
     * @inheritdoc
     */
    public function isSupported($entity)
    {
        return ($entity instanceof Tag);
    }

    /**
     * @inheritdoc
     */
    public function getAssets()
    {
        return array(
            '@LineStormPostBundle/Resources/public/js/post_tag.js'
        );
    }

    /**
     * @inheritdoc
     */
    public function getView($entity)
    {
        return new ComponentView('LineStormPostBundle:Component:Tag/view.html.twig');
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags', 'tag', array(
                'em' => $this->modelManager->getManager(),
                'tag_class' => $this->modelManager->getEntityClass('tag'),
                'name'  => 'name',
            ))
        ;
    }

    public function getRoutes(Loader $loader)
    {
        return $loader->import('@LineStormPostBundle/Resources/config/routing/component/tag.yml', 'yaml');
    }
} 
