<?php

namespace Proton\TutorialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Proton\TutorialBundle\Entity\Draft;
use Proton\TutorialBundle\Model\DraftInterface;
use Proton\TutorialBundle\Form\TutorialType;
use Proton\TutorialBundle\Entity\Tutorial;

class DraftController extends Controller
{

    public function listAction(Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }
        
        $drafts = $this->getDoctrine()->getRepository('ProtonTutorialBundle:Draft')->findBy(array(
            'author' => $this->getUser(),
        ), array(
            'updated_at' => 'DESC',
        ));

        return $this->render('ProtonTutorialBundle:Draft:list.html.twig', array(
            'drafts' => $drafts,
        ));
    }

    public function showAction(Draft $draft, Request $request)
    {
        if (!$this->canManage($draft)) {
            throw new AccessDeniedException();
        }

        return $this->render('ProtonTutorialBundle:Draft:show.html.twig', array(
            'draft' => $draft,
        ));
    }

    public function editAction(Draft $draft, Request $request)
    {
        if (!$this->canManage($draft)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new TutorialType(), $draft);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($draft);
                $em->flush();

                return $this->redirect($this->generateUrl('proton_tutorial_drafts_show', array(
                    'id' => $draft->getId(),
                )));
            }
        }

        return $this->render('ProtonTutorialBundle:Draft:edit.html.twig', array(
            'form' => $form->createView(),
            'draft' => $draft,
        ));
    }

    public function newAction(Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }

        $draft = new Draft();
        $form = $this->createForm(new TutorialType(), $draft);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $draft->setAuthor($this->getUser());
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($draft);
                $em->flush();

                return $this->redirect($this->generateUrl('proton_tutorial_drafts_show', array(
                    'id' => $draft->getId(),
                )));
            }
        }

        return $this->render('ProtonTutorialBundle:Draft:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function deleteAction(Draft $draft, Request $request)
    {
        if (!$this->canManage($draft)) {
            return new AccessDeniedException();
        }

        $form = $this->createFormBuilder(array('id' => $draft->getId()))
            ->add('id', 'hidden')
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->remove($draft);
                $em->flush();
                $this->container->get('session')->setFlash('notice', 'Draft trashed.');

                return $this->redirect($this->generateUrl('proton_tutorial_drafts_list'));
            }
        }

        return $this->render('ProtonTutorialBundle:Tutorial:delete.html.twig', array(
            'form' => $form->createView(),
            'tutorial' => $tutorial,
        ));
    }

    public function publishAction(Draft $draft, Request $request)
    {
        if (!$this->canManage($draft)) {
            throw new AccessDeniedException();
        }

        $tutorial = $this->container->get('proton_tutorial.manager.tutorial')->publishDraft($draft);

        return $this->redirect($this->generateUrl('proton_tutorial_tutorials_show', array(
            'slug' => $tutorial->getSlug(),
        )));
    }

    private function canManage(DraftInterface $draft)
    {
        $securityContext = $this->container->get('security.context');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return false;
        }

        return $securityContext->isGranted('ROLE_ADMIN') || $draft->getAuthor()->equals($this->getUser());
    }

}
