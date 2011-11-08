<?php

namespace Proton\QnABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proton\QnABundle\Entity\Question;
use Proton\QnABundle\Form\QuestionType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Proton\QnABundle\Form\AnswerType;

/**
 * Question controller.
 *
 * @Route("/questions")
 */
class QuestionController extends Controller
{
    /**
     * Lists all Question entities.
     *
     * @Route("/", name="question")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $questions = $em->getRepository('ProtonQnABundle:Question')->findBy(array(), array('created_at'=>'DESC'));

        return array('questions' => $questions);
    }

    /**
     * Displays a form to create a new Question entity.
     *
     * @Route("/ask", name="question_new")
     * @Template()
     */
    public function newAction()
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
        
        $entity = new Question();
        $form   = $this->createForm(new QuestionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Question entity.
     *
     * @Route("/create", name="question_create")
     * @Method("post")
     * @Template("ProtonQnABundle:Question:new.html.twig")
     */
    public function createAction()
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
        
        $entity  = new Question();
        $request = $this->getRequest();
        $form    = $this->createForm(new QuestionType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $entity->setAuthor($this->getUser());
            $this->getUser()->incrementQuestionCount();
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->persist($this->getUser());
            $em->flush();

            return $this->redirect($this->generateUrl('question_show', array('slug' => $entity->getSlug())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Question entity.
     *
     * @Route("/{id}/edit", name="question_edit")
     * @Template()
     */
    public function editAction($id)
    {
        if (!$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('ProtonQnABundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm = $this->createForm(new QuestionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Question entity.
     *
     * @Route("/{id}/update", name="question_update")
     * @Method("post")
     * @Template("ProtonQnABundle:Question:edit.html.twig")
     */
    public function updateAction($id)
    {
        if (!$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('ProtonQnABundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm   = $this->createForm(new QuestionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Question entity.
     *
     * @Route("/{id}/delete", name="question_delete")
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
            $entity = $em->getRepository('ProtonQnABundle:Question')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Question entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('question'));
    }

    /**
     * Finds and displays a Question entity.
     *
     * @Route("/{slug}/", name="question_show")
     * @Template()
     */
    public function showAction(Question $question)
    {
        $answer_form = $this->createForm(new AnswerType());

        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $answer = $this->getDoctrine()->getRepository('ProtonQnABundle:Answer')->findOneBy(array(
                'question' => $question,
                'author' => $this->getUser(),
            ));
            $can_answer = null === $answer;
        } else {
            $can_answer = false;
        }

        return array(
            'question' => $question,
            'answers' => $question->getAnswers(),
            'answer_form' => $answer_form->createView(),
            'can_answer' => $can_answer,
        );
    }
}
