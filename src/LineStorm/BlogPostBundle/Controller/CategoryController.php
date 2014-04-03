<?php

namespace LineStorm\BlogPostBundle\Controller;

use Doctrine\ORM\Query;
use LineStorm\BlogPostBundle\Model\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function displayAction($category)
    {
        $modelManager = $this->get('linestorm.blog.model_manager');

        $category = $modelManager->get('category')->findOneByName($category);

        if (!($category instanceof Category)) {
            throw $this->createNotFoundException("Category Not Found");
        }

        $posts = $modelManager->get('post')->findBy(array('category' => $category), array('liveOn' => 'DESC'), 10);

        return $this->render('LineStormBlogBundle:Category:display.html.twig', array(
            'category' => $category,
            'posts'    => $posts,
        ));
    }
}
