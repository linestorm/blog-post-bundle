<?php

namespace LineStorm\PostBundle\Controller;

use Doctrine\ORM\Query;
use LineStorm\PostBundle\Model\Post;
use LineStorm\PostBundle\Module\PostModule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PostController
 * @package LineStorm\PostBundle\Controller
 */
class PostController extends Controller
{

    /**
     * Display a post
     *
     * @param $category
     * @param $id
     * @param $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function displayAction($category, $id, $slug)
    {
        $modelManager = $this->get('linestorm.cms.model_manager');
        $moduleManager = $this->get('linestorm.cms.module_manager');

        /** @var PostModule $module */
        $module = $moduleManager->getModule('post');

        $post = $modelManager->get('post')->find($id);

        if(!($post instanceof Post))
        {
            throw $this->createNotFoundException("Post Not Found");
        }

        return $this->render('LineStormPostBundle:Post:display.html.twig', array(
            'post'   => $post,
            'module' => $module,
        ));
    }

}
