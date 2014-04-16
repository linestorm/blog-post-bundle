<?php

namespace LineStorm\PostBundle\Controller\Api;

use LineStorm\CmsBundle\Controller\Api\AbstractApiController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use LineStorm\PostBundle\Model\Post;

class PostController extends AbstractApiController implements ClassResourceInterface
{
    private function getForm($entity = null)
    {
        return $this->createForm('linestorm_cms_form_post', $entity);
    }

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



            $view = View::create(null, 201, array(
                'location' => $this->generateUrl('linestorm_cms_module_post_api_get_post', array( 'id' => $form->getData()->getId() ))
            ));
        } else {
            $view = View::create($form);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

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

            $view = $this->createResponse(array('location' => $this->generateUrl('linestorm_cms_module_post_api_get_post', array( 'id' => $form->getData()->getId()))), 200);
        }
        else
        {
            $view = View::create($form);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }


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
        $em->remove($post);
        $em->flush();


        $view = View::create(array(
            'message'  => 'Post has been deleted',
            'location' => $this->generateUrl('linestorm_cms_module_post_admin_list'),
        ));

        return $this->get('fos_rest.view_handler')->handle($view);

    }
}
