<?php

namespace LineStorm\BlogPostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends Controller
{
    public function listAction()
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->get('linestorm.blog.model_manager');

        $posts = $modelManager->get('post')->findAll();

        return $this->render('LineStormBlogPostBundle:Admin:list.html.twig', array(
            'posts' => $posts,
        ));
    }


    public function viewAction($id)
    {
        $moduleManager = $this->get('linestorm.blog.module_manager');
        $module        = $moduleManager->getModule('post');

        $modelManager = $this->get('linestorm.blog.model_manager');
        $post = $modelManager->get('post')->find($id);

        return $this->render('LineStormBlogPostBundle:Admin:view.html.twig', array(
            'post'      => $post,
            'module'    => $module,
        ));
    }


    public function editAction($id)
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $moduleManager = $this->get('linestorm.blog.module_manager');
        $module        = $moduleManager->getModule('post');

        $modelManager = $this->get('linestorm.blog.model_manager');
        $post = $modelManager->get('post')->find($id);

        $form = $this->createForm('linestorm_blog_form_post', $post, array(
            'action' => $this->generateUrl('linestorm_blog_post_module_api_put_post', array('id' => $post->getId())),
            'method' => 'PUT',
        ));

        return $this->render('LineStormBlogPostBundle:Admin:edit.html.twig', array(
            'post'      => $post,
            'form'      => $form->createView(),
            'module'    => $module,
        ));
    }


    public function newAction()
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm('linestorm_blog_form_post', null, array(
            'action' => $this->generateUrl('linestorm_blog_post_module_api_post_post'),
            'method' => 'POST',
        ));

        return $this->render('LineStormBlogPostBundle:Admin:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
