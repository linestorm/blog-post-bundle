<?php

namespace LineStorm\PostBundle\Module\Component;

use LineStorm\PostBundle\Model\PostArticle;
use LineStorm\PostBundle\Module\Component\View\ComponentView;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ArticleComponent
 * @package LineStorm\PostBundle\Module\Component
 */
class ArticleComponent extends AbstractBodyComponent implements ComponentInterface
{
    protected $name = 'Article';
    protected $id = 'articles';

    /**
     * @inheritdoc
     */
    public function isSupported($entity)
    {
        return ($entity instanceof PostArticle);
    }

    /**
     * @inheritdoc
     */
    public function getViewTemplate($entity)
    {
        return new ComponentView('LineStormPostBundle:Component:Article/view.html.twig');
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('articles', 'collection', array(
                'type'      => 'linestorm_cms_form_post_article',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'     => false,
            ))
        ;
    }

    /**
     * @inheritdoc
     */
    public function getRoutes(LoaderInterface $loader)
    {
        return null;
        // return $loader->import('@LineStormPostBundle/Resources/config/routing/modules/component/article.yml', 'rest');
    }
} 