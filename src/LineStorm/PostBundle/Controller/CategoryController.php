<?php

namespace LineStorm\PostBundle\Controller;

use Doctrine\ORM\Query;
use LineStorm\PostBundle\Model\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function displayAction($category)
    {
        $modelManager = $this->get('linestorm.cms.model_manager');

        $category = $modelManager->get('category')->findOneByName($category);

        if(!($category instanceof Category))
        {
            throw $this->createNotFoundException("Category Not Found");
        }

        $em    = $modelManager->getManager();
        $class = $modelManager->getEntityClass('post');

        $postModule = $this->get('linestorm.cms.module.post');

        // find the top X articles
        $dql = "
            SELECT
              p
            FROM
              {$class} p
            WHERE
                p.liveOn <= :date
                AND p.category = :category
            ORDER BY
                p.liveOn DESC
        ";

        $topPosts = $em->createQuery($dql)->setMaxResults($postModule->getPageSize())->setParameters(array(
            'date'     => new \DateTime(),
            'category' => $category
        ))->getResult();

        // get all their tags and categories
        $dql = "
            SELECT
                p,c,t
            FROM
                {$class} p
                JOIN p.category c
                JOIN p.tags t
            WHERE
                p IN (:posts)
            ORDER BY
                p.liveOn DESC
        ";

        $posts = $em->createQuery($dql)->setParameter('posts', $topPosts)->getResult();

        return $this->render('LineStormPostBundle:Category:display.html.twig', array(
            'category' => $category,
            'posts'    => $posts,
        ));
    }
}
