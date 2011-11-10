<?php

namespace Proton\QnABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Proton\QnABundle\Entity\Question;
use Proton\QnABundle\Form\QuestionType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionController extends Controller
{

    public function listAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $questions = $em->getRepository('ProtonQnABundle:Question')->findBy(array(
            'trashed' => false,
        ), array(
            'created_at'=>'DESC',
        ));

        return $this->render('ProtonQnABundle:Question:list.html.twig', array(
            'questions' => $questions,
        ));
    }

    public function showAction(Question $question)
    {
        if ($question->isTrashed() && !$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new NotFoundHttpException();
        }

        $canAnswer = false;
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $answer = $this->getDoctrine()->getRepository('ProtonQnABundle:Answer')->findOneBy(array(
                'question' => $question,
                'author' => $this->getUser(),
            ));
            $canAnswer = null === $answer;
        }

        $canEdit = false;
        if ($this->container->get('security.context')->isGranted('ROLE_ADMIN') ||
            ($this->getUser() instanceof UserInterface && $this->getUser()->equals($question->getAuthor()))) {
            $canEdit = true;
        }

        return $this->render('ProtonQnABundle:Question:show.html.twig', array(
            'question' => $question,
            'canAnswer' => $canAnswer,
            'canEdit' => $canEdit,
        ));
    }

    public function newAction(Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $question = new Question();
        $form = $this->createForm(new QuestionType(), $question);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $question->setAuthor($this->getUser());
                $this->getUser()->incrementQuestionCount();

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($question);
                $em->persist($this->getUser());
                $em->flush();

                return $this->redirect($this->generateUrl('proton_qna_questions_show', array(
                    'id' => $question->getId(),
                )));
            }
        }

        return $this->render('ProtonQnABundle:Question:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Question $question, Request $request)
    {
        if ($question->isTrashed() && !$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new NotFoundHttpException();
        }
        if (!$this->canManage($question)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new QuestionType(), $question);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();

                $em->persist($question);
                $em->flush();

                return $this->redirect($this->generateUrl('proton_qna_questions_show', array(
                    'slug' => $question->getSlug(),
                )));
            }
        }

        return $this->render('ProtonQnABundle:Question:edit.html.twig', array(
            'form' => $form->createView(),
            'question' => $question,
        ));
    }

    public function deleteAction(Question $question, Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $form = $this->createFormBuilder(array('id' => $question->getId()))
            ->add('id', 'hidden')
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $question->setTrashed(true);
                $question->getAuthor()->incrementQuestionCount(-1);
                $em->persist($question);
                $em->persist($question->getAuthor());
                $em->flush();

                return $this->redirect($this->generateUrl('proton_qna_questions_list'));
            }
        }

        return $this->render('ProtonQnABundle:Question:delete.html.twig', array(
            'form' => $form->createView(),
            'question' => $question,
        ));
    }

    private function canManage(Question $question)
    {
        return $this->container->get('security.context')->isGranted('ROLE_ADMIN') 
            || ($this->getUser() instanceof UserInterface && $this->getUser()->equals($question->getAuthor()));
    }
}
