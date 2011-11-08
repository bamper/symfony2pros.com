<?php

namespace Proton\QnABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proton\QnABundle\Entity\Answer;
use Proton\QnABundle\Form\AnswerType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Answer controller.
 *
 * @Route("/answer")
 */
class AnswerController extends Controller
{
    /**
     * Lists all Answer entities.
     *
     * @Route("/", name="answer")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('ProtonQnABundle:Answer')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Answer entity.
     *
     * @Route("/{id}/show", name="answer_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('ProtonQnABundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Creates a new Answer entity.
     *
     * @Route("/create/{question_id}", name="answer_create")
     * @Method("post")
     * @Template("ProtonQnABundle:Answer:new.html.twig")
     */
    public function createAction($question_id)
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $question = $this->getDoctrine()->getRepository('ProtonQnABundle:Question')->find($question_id);

        $entity  = new Answer();
        $entity->setQuestion($question);
        $request = $this->getRequest();
        $form    = $this->createForm(new AnswerType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $entity->setAuthor($this->getUser());
            $this->getUser()->incrementAnswerCount();
            $question->incrementAnswerCount();
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->persist($question);
            $em->persist($this->getUser());
            $em->flush();

            return $this->redirect($this->generateUrl('question_show', array('slug' => $question->getSlug())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Answer entity.
     *
     * @Route("/{id}/edit", name="answer_edit")
     * @Template()
     */
    public function editAction($id)
    {
        if (!$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('ProtonQnABundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $editForm = $this->createForm(new AnswerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Answer entity.
     *
     * @Route("/{id}/update", name="answer_update")
     * @Method("post")
     * @Template("ProtonQnABundle:Answer:edit.html.twig")
     */
    public function updateAction($id)
    {
        if (!$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('ProtonQnABundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $editForm   = $this->createForm(new AnswerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('answer_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Answer entity.
     *
     * @Route("/{id}/delete", name="answer_delete")
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
            $entity = $em->getRepository('ProtonQnABundle:Answer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Answer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('answer'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
