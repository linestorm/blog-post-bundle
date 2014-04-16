<?php

namespace LineStorm\PostBundle\Controller\Api;

use LineStorm\CmsBundle\Controller\Api\AbstractApiController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use LineStorm\PostBundle\Model\Post;

/**
 * API class for post model
 *
 * Class PostController
 *
 * @package LineStorm\PostBundle\Controller\Api
 */
class PostController extends AbstractApiController implements ClassResourceInterface
{
    /**
     * Creates a Posy type form
     *
     * @param null|Post $entity
     *
     * @return Form
     */
    private function getForm($entity = null)
    {
        return $this->createForm('linestorm_cms_form_post', $entity);
    }

    /**
     * Get a single post
     *
     * @param $id
     *
     * @return Response
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     */
    public function getAction($id)
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->getModelManager();

        $post = $modelManager->get('post')->find($id);
        if(!($post instanceof Post))
        {
            throw $this->createNotFoundException("Post not found");
        }

        $view = View::create($post);
        return $this->get('fos_rest.view_handler')->handle($view);

    }

    /**
     * Create a new post
     *
     * @return Response
     * @throws AccessDeniedException
     */
    public function postAction()
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->getModelManager();

        $request = $this->getRequest();
        $form = $this->getForm();

        $formValues = json_decode($request->getContent(), true);

        $form->submit($formValues['linestorm_cms_form_post']);

        if ($form->isValid()) {

            $em = $modelManager->getManager();
            $now = new \DateTime();

            /** @var Post $post */
            $post = $form->getData();
            $post->setAuthor($user);
            $post->setCreatedOn($now);

            $em->persist($post);
            $em->flush();

            // update the search provider!
            $searchManager = $this->get('linestorm.cms.module.search_manager');
            $postSearchProvider = $searchManager->get('post');
            $postSearchProvider->index($post);

            $view = View::create(null, 201, array(
                'location' => $this->generateUrl('linestorm_cms_module_post_api_get_post', array( 'id' => $form->getData()->getId() ))
            ));
        } else {
            $view = View::create($form);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Save a post
     *
     * @param $id
     *
     * @return Response
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     */
    public function putAction($id)
    {

        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->getModelManager();

        $post = $modelManager->get('post')->find($id);
        if(!($post instanceof Post))
        {
            throw $this->createNotFoundException("Post not found");
        }

        $request = $this->getRequest();
        $form = $this->getForm($post);

        $formValues = json_decode($request->getContent(), true);

        $form->submit($formValues['linestorm_cms_form_post']);

        if ($form->isValid())
        {
            $em = $modelManager->getManager();
            $now = new \DateTime();

            /** @var Post $updatedPost */
            $updatedPost = $form->getData();
            $updatedPost->setEditedBy($user);
            $updatedPost->setEditedOn($now);

            $em->persist($updatedPost);
            $em->flush();

            // update the search provider!
            $searchManager = $this->get('linestorm.cms.module.search_manager');
            $postSearchProvider = $searchManager->get('post');
            $postSearchProvider->index($updatedPost);

            $view = $this->createResponse(array('location' => $this->generateUrl('linestorm_cms_module_post_api_get_post', array( 'id' => $form->getData()->getId()))), 200);
        }
        else
        {
            $view = View::create($form);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Delete a post
     *
     * @param $id
     *
     * @return Response
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     */
    public function deleteAction($id)
    {

        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->getModelManager();

        $post = $modelManager->get('post')->find($id);
        if(!($post instanceof Post))
        {
            throw $this->createNotFoundException("Post not found");
        }

        $em = $modelManager->getManager();

        // remove indexes
        $searchManager = $this->get('linestorm.cms.module.search_manager');
        $postSearchProvider = $searchManager->get('post');
        $postSearchProvider->remove($post);

        $em->remove($post);
        $em->flush();

        $view = View::create(array(
            'message'  => 'Post has been deleted',
            'location' => $this->generateUrl('linestorm_cms_module_post_admin_list'),
        ));

        return $this->get('fos_rest.view_handler')->handle($view);

    }
}
