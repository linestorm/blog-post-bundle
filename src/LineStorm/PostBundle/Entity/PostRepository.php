<?php

namespace LineStorm\PostBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use LineStorm\PostBundle\Model\Post;

/**
 * Class PostRepository
 *
 * @package LineStorm\PostBundle\Entity
 */
class PostRepository extends EntityRepository
{
    /**
     * @param  string  $categoryName
     * @param int|null $limit
     *
     * @return Post[]
     */
    public function findAllByCategoryName($categoryName, $limit = null)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p,c,t');

        $qb->join('p.category', 'c')
           ->join('p.tags', 't');

        $qb->where('c.name = :name');

        $query = $qb->getQuery();

        $query->setParameters(array(
            'name' => $categoryName
        ));

        return $query->setMaxResults($limit)->getResult();
    }

    /**
     * @return array
     */
    public function findTopTen()
    {
        $qb = $this->createQueryBuilder('p');

        // get the top 10
        $qb->select('partial p.{id}');
        $qb->andwhere('p.liveOn < :now')
           ->setMaxResults(10)
           ->setParameter('now', new \DateTime());

        $query = $qb->getQuery();
        $topTen = $query->getArrayResult();
        $topTenIds = array_map(function($post){
            return $post['id'];
        },$topTen);

        $qb = $this->createQueryBuilder('p');

        // get categoty and tags as well
        $qb->select('p,c,t,i');
        $qb->join('p.category', 'c')
           ->join('p.tags', 't')
           ->join('p.coverImage', 'i')
           ->andwhere('p.id IN (:posts)')
           ->setParameter('posts', $topTenIds);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * @param $post
     *
     * @return array
     */
    public function findRelated(Post $post)
    {
        $qb = $this->createQueryBuilder('p');

        // get categoty and tags as well
        $qb->select('p,t,p2');
        $qb->join('p.tags', 't')
           ->join('t.posts',  'p2', Join::WITH, 'p.id != p2.id');

        $qb->andWhere('p = :post')
           ->andWhere('p2.liveOn < :now')
           ->andWhere('p.liveOn  < :now');

        $qb->groupBy('p2');

        $query = $qb->getQuery()->setMaxResults(5);

        $query->setParameters(array(
            'now'  => new \DateTime(),
            'post' => $post,
        ));

        $related = new ArrayCollection();

        try
        {
            $result = $query->getSingleResult();
            foreach($result->getTags() as $tag)
            {
                foreach($tag->getPosts() as $rPost)
                {
                    if(!$related->contains($rPost))
                        $related[] = $rPost;
                }
            }
        }
        catch(NoResultException $e)
        {
            // just return an empty collection
        }

        return $related;
    }



}
