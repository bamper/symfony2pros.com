<?php

namespace Proton\TutorialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proton\TutorialBundle\Entity\Tutorial;
use Proton\TutorialBundle\Form\TutorialType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Tutorial controller.
 *
 * @Route("/tutorials")
 */
class TutorialController extends Controller
{
    /**
     * Lists all Tutorial entities.
     *
     * @Route("/", name="tutorial")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $tutorials = $em->getRepository('ProtonTutorialBundle:Tutorial')->findBy(array(), array('created_at' => 'DESC'));

        return array('tutorials' => $tutorials);
    }

    /**
     * Displays a form to create a new Tutorial entity.
     *
     * @Route("/new", name="tutorial_new")
     * @Template()
     */
    public function newAction()
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $entity = new Tutorial();
        $form   = $this->createForm(new TutorialType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Tutorial entity.
     *
     * @Route("/create", name="tutorial_create")
     * @Method("post")
     * @Template("ProtonTutorialBundle:Tutorial:new.html.twig")
     */
    public function createAction()
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $entity  = new Tutorial();
        $request = $this->getRequest();
        $form    = $this->createForm(new TutorialType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $entity->setAuthor($this->getUser());
            $this->getUser()->incrementTutorialCount();
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->persist($this->getUser());
            $em->flush();

            return $this->redirect($this->generateUrl('tutorial_show', array('slug' => $entity->getSlug())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Tutorial entity.
     *
     * @Route("/{id}/edit", name="tutorial_edit")
     * @Template()
     */
    public function editAction(Tutorial $tutorial)
    {
        if (!$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm(new TutorialType(), $tutorial);
        $deleteForm = $this->createDeleteForm($tutorial->getId());

        return array(
            'entity'      => $tutorial,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Tutorial entity.
     *
     * @Route("/{id}/update", name="tutorial_update")
     * @Method("post")
     * @Template("ProtonTutorialBundle:Tutorial:edit.html.twig")
     */
    public function updateAction($id)
    {
        if (!$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('ProtonTutorialBundle:Tutorial')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tutorial entity.');
        }

        $editForm   = $this->createForm(new TutorialType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tutorial_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Tutorial entity.
     *
     * @Route("/{id}/delete", name="tutorial_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        if (!$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('ProtonTutorialBundle:Tutorial')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tutorial entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tutorial'));
    }

    /**
     * Finds and displays a Tutorial entity.
     *
     * @Route("/{slug}", name="tutorial_show")
     * @Template()
     */
    public function showAction(Tutorial $tutorial)
    {
        return array(
            'tutorial'      => $tutorial,
        );
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
