<?php

namespace LineStorm\BlogPostBundle\Controller;

use Doctrine\ORM\Query;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use LineStorm\BlogPostBundle\Model\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PostController extends Controller
{

    public function displayAction($category, $id, $slug)
    {
        $modelManager = $this->get('linestorm.blog.model_manager');

        $mediaManager = $this->get('linestorm.blog.media_manager');

        $post = $modelManager->get('post')->find($id);

        if(!($post instanceof Post))
        {
            throw $this->createNotFoundException("Post Not Found");
        }

        return $this->render('LineStormBlogBundle:Post:display.html.twig', array(
            'post' => $post,
        ));
    }

}
