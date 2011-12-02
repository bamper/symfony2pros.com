<?php

namespace Proton\QnABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Proton\QnABundle\Entity\Answer;
use Proton\QnABundle\Form\AnswerType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Proton\QnABundle\Entity\Question;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class AnswerController extends Controller
{

    public function listAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $answers = $em->getRepository('ProtonQnABundle:Answer')->findBy(array(
            'trashed' => false,
        ), array(
            'created_at'=>'DESC',
        ));

        return $this->render('ProtonQnABundle:Answer:list.html.twig', array(
            'answers' => $answers,
        ));
    }

    public function showAction(Answer $answer)
    {
        if ($answer->isTrashed() && !$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new NotFoundHttpException();
        }

        return $this->render('ProtonQnABundle:Answer:show.html.twig', array(
            'answer' => $answer,
        ));
    }

    public function newAction(Question $question, Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }

        $answer = new Answer();
        $form = $this->createForm(new AnswerType(), $answer);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $answer->setQuestion($question);
                $answer->setAuthor($this->getUser());

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($answer);
                $em->flush();

                $redis = $this->container->get('snc_redis.default_client');
                $redis->hincrby(sprintf('user:%d', $answer->getAuthor()->getId()), 'answer_count', 1);

                return $this->redirect($this->generateUrl('proton_qna_questions_show', array(
                    'slug' => $question->getSlug(),
                ))."#answer-{$answer->getId()}");
            }
        }

        return $this->render('ProtonQnABundle:Answer:new.html.twig', array(
            'form' => $form->createView(),
            'question' => $question,
        ));
    }

    public function editAction(Answer $answer, Request $request)
    {
        if ($answer->isTrashed() && !$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new NotFoundHttpException();
        }
        if (!$this->canManage($answer)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new AnswerType(), $answer);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();

                $em->persist($answer);
                $em->flush();

                $this->container->get('session')->setFlash('notice', 'Your changes have been saved.');

                return $this->redirect($this->generateUrl('proton_qna_questions_show', array(
                    'slug' => $answer->getQuestion()->getSlug(),
                )));
            }
        }

        return $this->render('ProtonQnABundle:Answer:edit.html.twig', array(
            'form' => $form->createView(),
            'answer' => $answer,
        ));
    }

    public function deleteAction(Answer $answer, Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $form = $this->createFormBuilder(array('id' => $answer->getId()))
            ->add('id', 'hidden')
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $answer->setTrashed(true);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($answer);
                $em->flush();

                $redis = $this->container->get('snc_redis.default_client');
                $redis->hincrby(sprintf('user:%d', $answer->getAuthor()->getId()), 'answer_count', -1);

                $this->container->get('session')->setFlash('notice', 'Answer trashed.');

                return $this->redirect($this->generateUrl('proton_qna_answers_list'));
            }
        }

        return $this->render('ProtonQnABundle:Answer:delete.html.twig', array(
            'form' => $form->createView(),
            'answer' => $answer,
        ));
    }

    private function canManage(Answer $answer)
    {
        return $this->container->get('security.context')->isGranted('ROLE_ADMIN')
            || ($this->getUser() instanceof UserInterface && $this->getUser()->equals($answer->getAuthor()));
    }
    
}
