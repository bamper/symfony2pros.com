<?php

namespace Proton\TutorialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Proton\TutorialBundle\Entity\Tutorial;
use Proton\TutorialBundle\Form\TutorialType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

class TutorialController extends Controller
{

    public function listAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $tutorials = $em->getRepository('ProtonTutorialBundle:Tutorial')->findBy(array(
            'trashed' => false,
        ), array(
            'created_at' => 'DESC',
        ));

        return $this->render('ProtonTutorialBundle:Tutorial:list.html.twig', array(
            'tutorials' => $tutorials,
        ));
    }

    public function showAction(Tutorial $tutorial)
    {
        if ($tutorial->isTrashed() && !$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new NotFoundHttpException();
        }

        $canEdit = false;
        if ($this->container->get('security.context')->isGranted('ROLE_ADMIN') ||
            ($this->getUser() instanceof UserInterface && $this->getUser()->equals($tutorial->getAuthor()))) {
            $canEdit = true;
        }

        if (!$this->getUser() instanceof UserInterface || !$this->getUser()->equals($tutorial->getAuthor())) {
            $tutorial->incrementViews();
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($tutorial);
            $em->flush();
        }

        return $this->render('ProtonTutorialBundle:Tutorial:show.html.twig', array(
            'tutorial' => $tutorial,
            'canEdit' => $canEdit,
        ));
    }

    public function newAction(Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $entity = new Tutorial();
        $form   = $this->createForm(new TutorialType(), $entity);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $entity->setAuthor($this->getUser());
                $this->getUser()->incrementTutorialCount();
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->persist($this->getUser());
                $em->flush();

                return $this->redirect($this->generateUrl('proton_tutorial_tutorials_show', array(
                    'slug' => $entity->getSlug()
                )));
            }
        }

        return $this->render('ProtonTutorialBundle:Tutorial:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    public function editAction(Tutorial $tutorial, Request $request)
    {
        if ($tutorial->isTrashed() && !$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new NotFoundHttpException();
        }
        if (!$this->canManage($tutorial)) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new TutorialType(), $tutorial);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em->persist($tutorial);
                $em->flush();

                $this->container->get('session')->setFlash('notice', 'Your changes have been saved.');

                return $this->redirect($this->generateUrl('proton_tutorial_tutorials_show', array(
                    'slug' => $tutorial->getSlug(),
                )));
            }
        }

        return $this->render('ProtonTutorialBundle:Tutorial:edit.html.twig', array(
            'tutorial'      => $tutorial,
            'form'        => $form->createView(),
        ));
    }

    public function deleteAction(Tutorial $tutorial, Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $form = $this->createFormBuilder(array('id' => $tutorial->getId()))
            ->add('id', 'hidden')
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $tutorial->setTrashed(true);
                $tutorial->getAuthor()->incrementTutorialCount(-1);
                $em->persist($tutorial);
                $em->persist($tutorial->getAuthor());
                $em->flush();

                $this->container->get('session')->setFlash('notice', 'Tutorial trashed.');

                return $this->redirect($this->generateUrl('proton_tutorial_tutorials_list'));
            }
        }

        return $this->render('ProtonTutorialBundle:Tutorial:delete.html.twig', array(
            'form' => $form->createView(),
            'tutorial' => $tutorial,
        ));
    }

    private function canManage(Tutorial $tutorial)
    {
        $securityUser = $this->container->get('security.context')->getToken()->getUser();

        return $this->container->get('security.context')->isGranted('ROLE_ADMIN')
            || ($securityUser instanceof UserInterface && $securityUser->equals($tutorial->getAuthor()));
    }

}
