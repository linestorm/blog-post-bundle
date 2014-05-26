<?php

namespace LineStorm\PostBundle\Controller;

use Doctrine\ORM\Query;
use LineStorm\TagComponentBundle\Model\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TagController extends Controller
{

    public function displayAction($tag)
    {
        $modelManager = $this->get('linestorm.cms.model_manager');

        $tagEntity = $modelManager->get('tag')->findOneByName($tag);

        if (!($tagEntity instanceof Tag))
        {
            throw $this->createNotFoundException("Tag Not Found");
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
              JOIN p.tags t WITH t = :tag
            WHERE
                p.liveOn <= :date
            ORDER BY
                p.liveOn DESC
        ";

        $topPosts = $em->createQuery($dql)->setMaxResults($postModule->getPageSize())->setParameters(array(
            'date'     => new \DateTime(),
            'tag' => $tagEntity
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

        return $this->render('LineStormPostBundle:Tag:display.html.twig', array(
            'tag'   => $tagEntity,
            'posts' => $posts,
        ));
    }

}
