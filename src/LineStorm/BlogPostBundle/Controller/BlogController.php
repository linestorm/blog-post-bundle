<?php

namespace LineStorm\BlogPostBundle\Controller;

use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    public function indexAction()
    {
        $modelManager = $this->get('linestorm.blog.model_manager');

        $posts = $modelManager->get('post')->findBy(array(), array('liveOn' => 'DESC'), 10);

        return $this->render('LineStormBlogBundle:Pages:index.html.twig', array(
            'posts' => $posts,
        ));
    }

}
