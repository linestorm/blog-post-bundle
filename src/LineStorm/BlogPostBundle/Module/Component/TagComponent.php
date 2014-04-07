<?php

namespace LineStorm\BlogPostBundle\Module\Component;

use LineStorm\BlogPostBundle\Model\Post;
use LineStorm\BlogPostBundle\Model\Tag;
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
        return 'LineStormBlogPostBundle:Admin:Component/tag/form-assets.html.twig';
    }

    /**
     * @param $entity Tag
     * @return string
     */
    public function getViewTemplate($entity)
    {
        return null;
    }

    public function getNewTemplate()
    {
        $tags = $this->modelManager->get('tag')->findBy(array(), array('name' => 'ASC'));

        return $this->templating->render('LineStormBlogBundle:Modules:Post/Component/tag/new.html.twig', array(
            'tagEntities'   => null,
            'component'     => $this,
            'tags'          => $tags,
        ));
    }

    /**
     * @param $entity Tag
     * @return string
     */
    public function getEditTemplate($entity)
    {
        $tags = $this->modelManager->get('tag')->findBy(array(), array('name' => 'ASC'));

        return $this->templating->render('LineStormBlogBundle:Modules:Post/Component/tag/new.html.twig', array(
            'tagEntities'   => $entity,
            'component'     => $this,
            'tags'          => $tags,
        ));
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
