<?php

namespace LineStorm\PostBundle\Module\Component;

use LineStorm\PostBundle\Model\Post;
use LineStorm\PostBundle\Model\Tag;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;

class TagComponent extends AbstractMetaComponent implements ComponentInterface
{
    protected $name = 'Tag';
    protected $id = 'tags';

    public function isSupported($entity)
    {
        return ($entity instanceof Tag);
    }

    public function getFormAssetTemplate()
    {
        return 'LineStormPostBundle:Admin:Component/tag/form-assets.html.twig';
    }

    /**
     * @param $entity Tag
     * @return string
     */
    public function getViewTemplate($entity)
    {
        return null;
    }

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


    public function handleSave(Post $post, array $data)
    {
        $entities = array();

        foreach ($data as $eData) {
            $tag = $this->getEntityByName($eData);
            if (!($tag instanceof Tag)) {
                $tag = $this->createEntity($eData);
            }
            $post->addTag($tag);
            $entities[] = $tag;
        }

        return $entities;
    }

    public function getEntityByName(array $data)
    {
        return $this->modelManager->get('tag')->findOneBy(array(
            'name' => $data['name']
        ));
    }

    public function createEntity(array $data)
    {
        $class  = $this->modelManager->getEntityClass('tag');
        $entity = new $class();

        $entity->setName($data['name']);

        return $entity;
    }

    public function getRoutes(LoaderInterface $loader)
    {
        return null;
    }
} 
