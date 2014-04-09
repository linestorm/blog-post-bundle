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

        if (!($category instanceof Category)) {
            throw $this->createNotFoundException("Category Not Found");
        }

        $em = $modelManager->getManager();
        $class = $modelManager->getEntityClass('post');

        $dql = "
            SELECT
                p,c,t
            FROM
                {$class} p
                JOIN p.category c
                JOIN p.tags t
            WHERE
                p.liveOn <= :date
            ORDER BY
                p.liveOn DESC
        ";

        $posts = $em->createQuery($dql)->setMaxResults(20)->setParameter('date', new \DateTime())->getResult();

        return $this->render('LineStormPostBundle:Category:display.html.twig', array(
            'category' => $category,
            'posts'    => $posts,
        ));
    }
}
