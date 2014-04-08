<?php

namespace LineStorm\PostBundle\Controller;

use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    public function indexAction()
    {
        $modelManager = $this->get('linestorm.cms.model_manager');

        $posts = $modelManager->get('post')->findBy(array(), array('liveOn' => 'DESC'), 10);

        return $this->render('LineStormPostBundle:Pages:index.html.twig', array(
            'posts' => $posts,
        ));
    }

}
