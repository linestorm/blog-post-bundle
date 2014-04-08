<?php

namespace LineStorm\PostBundle\Controller;

use Doctrine\ORM\Query;
use LineStorm\PostBundle\Model\Tag;
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

        $postClass = $modelManager->getEntityClass('post');
        $dql = "
            SELECT
                p
            FROM
                {$postClass} p
            WHERE
                :tag MEMBER OF p.tags
            ORDER BY
                p.liveOn DESC
        ";
        $posts = $modelManager->getManager()->createQuery($dql)->setParameter('tag', $tagEntity)->setMaxResults(10)->getResult();

        return $this->render('LineStormPostBundle:Tag:display.html.twig', array(
            'tag'   => $tagEntity,
            'posts' => $posts,
        ));
    }

}
