<?php

namespace LineStorm\PostBundle\Search;

use Andy\PortfolioBundle\Entity\BlogPost;
use Doctrine\ORM\QueryBuilder;
use LineStorm\SearchBundle\Search\Provider\FullTextSearchProvider;
use LineStorm\SearchBundle\Search\SearchProviderInterface;

/**
 * Class PostTriGraphSearchProvider
 *
 * @package LineStorm\PostBundle\Search
 */
class PostFullTextSearchProvider extends FullTextSearchProvider implements SearchProviderInterface
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function getTriGraph()
    {
        return 'trigraph_post';
    }

    /**
     * @inheritdoc
     */
    public function queryBuilder(QueryBuilder $qb, $alias)
    {
        $qb->addSelect('c')
            ->join($alias.'.category', 'c');

        $qb->addSelect('ta')
            ->join($alias.'.tags', 'ta');
    }

    /**
     * @inheritdoc
     */
    public function getRoute($entity)
    {
        if($entity instanceof BlogPost)
        {
            return array(
                'linestorm_cms_post',
                array(
                    'category' => $entity->getCategory()->getName(),
                    'id'       => $entity->getId(),
                    'slug'     => $entity->getSlug(),
                )
            );
        }
        elseif(is_array($entity))
        {
            return array(
                'linestorm_cms_post',
                array(
                    'category' => $entity['category']['name'],
                    'id'       => $entity['id'],
                    'slug'     => $entity['slug'],
                )
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function getIndexFields()
    {
        return array(
            'title' => null,
            'articles' => array(
                'body'
            ),
            'galleries' => array(
                'body'
            ),
            'polls' => array(
                'body'
            ),
            'category' => array(
                'name'
            ),
            'tags' => array(
                'name'
            )
        );
    }


} 
