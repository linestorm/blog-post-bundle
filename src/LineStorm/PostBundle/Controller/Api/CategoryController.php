<?php

namespace LineStorm\PostBundle\Controller\Api;

use Doctrine\ORM\Query;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use LineStorm\CmsBundle\Controller\Api\AbstractApiController;
use LineStorm\PostBundle\Model\Category;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class CategoryController extends AbstractApiController implements ClassResourceInterface
{
    private $formName = 'linestorm_cms_form_post_category';

    private function getForm($entity = null)
    {
        return $this->createForm($this->formName, $entity);
    }


    public function newAction()
    {

        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm($this->formName, null, array(
            'action' => $this->generateUrl('linestorm_cms_module_post_api_post_category'),
            'method' => 'POST',
        ));

        $view = $form->createView();

        /** @var \Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper $tpl */
        $tpl = $this->get('templating.helper.form');
        $form = $tpl->form($view);

        $rView = View::create(array(
            'form' => $form
        ));

        return $this->get('fos_rest.view_handler')->handle($rView);
    }

    public function cgetAction()
    {
        $modelManager = $this->getModelManager();

        $categories = $modelManager->get('category')->findAll();

        $view = View::create($categories);
        return $this->get('fos_rest.view_handler')->handle($view);

    }

    public function getAction($id)
    {
        $modelManager = $this->getModelManager();

        $category = $modelManager->get('category')->find($id);
        if(!($category instanceof Category))
        {
            throw $this->createNotFoundException("Category not found");
        }

        $view = View::create($category);
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

        $form->submit($formValues[$this->formName]);

        if ($form->isValid()) {

            $em = $modelManager->getManager();
            $now = new \DateTime();

            /** @var Category $category */
            $category = $form->getData();
            $category->setCreatedOn($now);
            $category->setEditedOn($now);

            $em->persist($category);
            $em->flush();

            $view = View::create($category, 201, array(
                'location' => $this->generateUrl('linestorm_cms_module_post_api_get_category', array( 'id' => $category->getId() ))
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

        $category = $modelManager->get('category')->find($id);
        if(!($category instanceof Category))
        {
            throw $this->createNotFoundException("Category not found");
        }

        $request = $this->getRequest();
        $form = $this->getForm($category);

        $formValues = json_decode($request->getContent(), true);

        $form->submit($formValues[$this->formName]);

        if ($form->isValid())
        {
            $em = $modelManager->getManager();
            $now = new \DateTime();

            /** @var Category $updatedCategory */
            $updatedCategory = $form->getData();
            $updatedCategory->setEditedOn($now);

            $em->persist($updatedCategory);
            $em->flush();

            $view = $this->createResponse(array('location' => $this->generateUrl('linestorm_cms_module_post_api_get_category', array( 'id' => $updatedCategory->getId()))), 200);
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

        $category = $modelManager->get('category')->find($id);
        if(!($category instanceof Category))
        {
            throw $this->createNotFoundException("Category not found");
        }

        $em = $modelManager->getManager();
        $em->remove($category);

        $view = View::create(null);

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}
