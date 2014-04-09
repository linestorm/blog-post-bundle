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

        return $this->render('LineStormPostBundle:Pages:index.html.twig', array(
            'posts' => $posts,
        ));
    }

}
