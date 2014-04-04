<?php

namespace LineStorm\BlogPostBundle\Module\Component;

use LineStorm\BlogPostBundle\Form\BlogPostArticleType;
use LineStorm\BlogPostBundle\Model\Post;
use LineStorm\BlogPostBundle\Model\PostArticle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;

class ArticleComponent extends AbstractBodyComponent implements ComponentInterface
{
    protected $name = 'Article';
    protected $id = 'articles';

    public function isSupported($entity)
    {
        return ($entity instanceof PostArticle);
    }

    /**
     * @param $entity PostArticle
     * @return string
     */
    public function getViewTemplate($entity)
    {
        return $this->templating->render('LineStormBlogBundle:Modules:Post/Component/article/view.html.twig', array(
            'article' => $entity,
        ));
    }

    public function getNewTemplate()
    {
        return $this->templating->render('LineStormBlogBundle:Modules:Post/Component/article/new.html.twig', array(
            'articles' => null,
        ));
    }

    /**
     * @param $entity PostArticle
     * @return string
     */
    public function getEditTemplate($entity)
    {
        return $this->templating->render('LineStormBlogBundle:Modules:Post/Component/article/new.html.twig', array(
            'articles' => $entity,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('articles', 'collection', array(
                'type'      => 'linestorm_blog_form_post_article',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'     => false,
            ))
        ;
    }

    public function handleSave(Post $post, array $data)
    {
        $entities = array();

        foreach($data as $i => $eData)
        {
            $eData['order'] = $i;
            $article = $this->createEntity($eData);
            $post->addArticle($article);
            $entities[] = $article;
        }

        return $entities;
    }

    public function createEntity(array $data)
    {
        /** @var PostArticle $class */
        $class = $this->modelManager->getEntityClass('post_article');
        $entity = new $class();

        $entity->setOrder($data['order']);
        $entity->setBody($data['body']);

        return $entity;
    }

    public function getRoutes(LoaderInterface $loader)
    {
        return null;
        // return $loader->import('@LineStormBlogBundle/Resources/config/routing/modules/component/article.yml', 'rest');
    }
} 
