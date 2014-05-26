<?php

namespace LineStorm\PostBundle\Controller;

use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    public function indexAction()
    {
        $modelManager = $this->get('linestorm.cms.model_manager');

        $em = $modelManager->getManager();
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
            ORDER BY
                p.liveOn DESC
        ";

        $posts = $em->createQuery($dql)->setMaxResults($postModule->getPageSize())->setParameter('date', new \DateTime())->getResult();

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

        $posts = $em->createQuery($dql)->setParameter('posts', $posts)->getResult();

        return $this->render('LineStormPostBundle:Pages:index.html.twig', array(
            'posts' => $posts,
        ));
    }

}
