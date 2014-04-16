<?php

namespace LineStorm\PostBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
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

        // get categoty and tags as well
        $qb->select('p,c,t,i');
        $qb->join('p.category', 'c')
           ->join('p.tags', 't')
           ->join('p.coverImage', 'i');

        $query = $qb->getQuery()->setMaxResults(10);
        return $query->getResult();
    }

    /**
     * @param $post
     *
     * @return array
     */
    public function findRelated(Post $post)
    {
        /*

                $em = $this->getEntityManager();
                $meta = $this->getClassMetadata();

                $tagMeta = $meta->getAssociationMapping('tags');
                var_dump($tagMeta['joinTable']['name']);

                $sql = "
                    SELECT
                        p2.*
                    FROM
                        blog_post p
                        INNER JOIN blog_post_tag bt ON p.id=bt.blogpost_id
                        INNER JOIN blog_post_tag bt2 ON bt.blogtag_id=bt2.blogtag_id AND bt.blogpost_id <> bt2.blogpost_id
                        INNER JOIN blog_post p2 ON p2.id = bt2.blogpost_id
                    WHERE
                        p.id = ?1
                    GROUP BY
                        p2.id
                ";

                $rsm = new ResultSetMappingBuilder($em);
                $rsm->addRootEntityFromClassMetadata($this->getClassName(), 'p2');

                $sqlQuery = $em->createNativeQuery($sql, $rsm);
                $sqlQuery->setParameter(1, $post->getId());
                $results =  $sqlQuery->getResult();

                var_dump(count($results));
                die();
        */

        $qb = $this->createQueryBuilder('p');

        // get categoty and tags as well
        $qb->select('p,t,p2');
        $qb->join('p.tags', 't')
           ->join('t.posts',  'p2', Join::WITH, 'p.id != p2.id');

        $qb->andWhere('p = :post');

        $qb->groupBy('p2');

        $query = $qb->getQuery()->setMaxResults(5);

        $query->setParameters(array(
            'post' => $post,
        ));

        $result = $query->getSingleResult();

        $related = new ArrayCollection();

        foreach($result->getTags() as $tag)
        {
            foreach($tag->getPosts() as $rPost)
            {
                if(!$related->contains($rPost))
                    $related[] = $rPost;
            }
        }

        return $related;
    }


}
