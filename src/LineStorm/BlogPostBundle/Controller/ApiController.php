<?php

namespace LineStorm\BlogPostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use LineStorm\BlogBundle\Model\ModelManager;
use LineStorm\BlogBundle\Module\ModuleManager;
use LineStorm\BlogPostBundle\Model\Post;

class ApiController extends Controller implements ClassResourceInterface
{
    private $modelManager = null;

    private $moduleManager = null;

    private function createResponse($data = null, $code = 200, $headers = array())
    {
        return View::create($data, $code, $headers)
            ->setFormat('json');
    }

    /**
     * @return ModelManager
     */
    private function getModelManager()
    {
        if(!$this->modelManager)
            $this->modelManager = $this->get('linestorm.blog.model_manager');

        return $this->modelManager;
    }

    /**
     * @return ModuleManager
     */
    private function getModuleManager()
    {
        if(!$this->moduleManager)
            $this->moduleManager = $this->get('linestorm.blog.module_manager');

        return $this->moduleManager;
    }

    private function getForm($entity = null)
    {
        return $this->createForm('linestorm_blog_form_post', $entity);
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

        $form->submit($formValues['linestorm_blog_form_post']);

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
                'location' => $this->generateUrl('linestorm_blog_admin_module_post_api_post_get_post', array( 'id' => $form->getData()->getId() ))
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

        $form->submit($formValues['linestorm_blog_form_post']);

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

            $view = $this->createResponse(array('location' => $this->generateUrl('linestorm_blog_admin_module_post_api_post_get_post', array( 'id' => $form->getData()->getId()))), 200);
        }
        else
        {
            $view = View::create($form);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

}
