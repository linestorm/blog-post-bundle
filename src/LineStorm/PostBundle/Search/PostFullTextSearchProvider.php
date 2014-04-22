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
    public function getModel()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function getIndexModel()
    {
        return 'search_fulltext_post';
    }

    /**
     * @inheritdoc
     */
    public function queryBuilder(QueryBuilder $qb, $alias)
    {
        $now = new \DateTime();
        $qb->andWhere($alias.'liveOn < :now')
            ->setParameter('now', $now);

        $qb->addSelect('c')
            ->join($alias.'.category', 'c')
            ->addGroupBy('c.id');

        $qb->addSelect('ta')
            ->join($alias.'.tags', 'ta')
            ->addGroupBy('ta.id');
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


} 
