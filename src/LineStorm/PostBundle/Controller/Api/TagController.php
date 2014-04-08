<?php

namespace LineStorm\PostBundle\Controller\Api;

use Doctrine\ORM\Query;
use FOS\RestBundle\Routing\ClassResourceInterface;
use LineStorm\CmsBundle\Controller\Api\AbstractApiController;
use Symfony\Component\Intl\Exception\MethodNotImplementedException;

class TagController extends AbstractApiController implements ClassResourceInterface
{

    /**
     * Get a list of all consumables
     *
     * [GET] /api/blog/tags.{_format}
     */
    public function cgetAction()
    {
        $modelManager = $this->getModelManager();

        $em = $modelManager->getManager();

        $dql = "
            SELECT
                partial t.{id,name}
            FROM
                {$modelManager->getEntityClass('tag')} t
        ";
        $tags = $em->createQuery($dql)->getArrayResult();

        $view = $this->createResponse($tags);

        return $this->get('fos_rest.view_handler')->handle($view);
    }


    /**
     * Get a list of all consumables
     *
     * [GET] /api/blog/tags/query.{_format}
     */
    public function queryAction()
    {
        $modelManager = $this->getModelManager();

        $em = $modelManager->getManager();

        $query = $this->getRequest()->query->get('q', '');
        $maxResults = $this->getRequest()->query->get('limit', 10);
        if(!is_numeric($maxResults) || $maxResults < 0 || $maxResults > 50)
        {
            $maxResults = 10;
        }

        $dql = "
            SELECT
                partial t.{id,name}
            FROM
                {$modelManager->getEntityClass('tag')} t
            WHERE
                t.name LIKE :query
            ORDER BY
                t.name
        ";
        $tags = $em->createQuery($dql)->setParameter('query', $query)->setMaxResults($maxResults)->getArrayResult();

        $view = $this->createResponse($tags);

        return $this->get('fos_rest.view_handler')->handle($view);
    }


    /**
     * Get a post
     *
     * [GET] /api/blog/post/{id}.{_format}
     */
    public function getAction($id)
    {
        throw new MethodNotImplementedException(__METHOD__);
    }

}
