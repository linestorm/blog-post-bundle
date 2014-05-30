<?php

namespace LineStorm\PostBundle\Controller;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use LineStorm\PostBundle\Model\Post;
use LineStorm\PostBundle\Module\PostModule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PostController
 * @package LineStorm\PostBundle\Controller
 */
class PostController extends Controller
{

    /**
     * Display a post
     *
     * @param $category
     * @param $id
     * @param $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function displayAction($category, $id, $slug)
    {
        $modelManager = $this->get('linestorm.cms.model_manager');
        $moduleManager = $this->get('linestorm.cms.module_manager');

        /** @var PostModule $module */
        $module = $moduleManager->getModule('post');

        $em = $modelManager->getManager();
        $repo = $modelManager->get('post');

        $qb = $repo->createQueryBuilder('p')
            ->select('p,c,t,a')
            ->join('p.category', 'c')
            ->join('p.tags', 't')
            ->join('p.author', 'a')
            ->where('p.id = ?1')
        ;


        $user = $this->getUser();
        if (!(($user instanceof UserInterface) && ($user->hasGroup('admin'))))
        {
            $qb->andWhere('p.liveOn <= :date')->setParameter('date', new \DateTime());
        }


        try
        {
            $post = $qb->getQuery()->setParameter(1, $id)->getSingleResult();
        }
        catch(NonUniqueResultException $e)
        {
            throw $this->createNotFoundException("Post Not Found", $e);
        }
        catch(NoResultException $e)
        {
            throw $this->createNotFoundException("Post Not Found", $e);
        }

        if(!($post instanceof Post))
        {
            throw $this->createNotFoundException("Post Not Found");
        }

        return $this->render('LineStormPostBundle:Post:display.html.twig', array(
            'post'   => $post,
            'module' => $module,
        ));
    }

}
