<?php

namespace Proton\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends Controller
{

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $questions = $em->getRepository('ProtonQnABundle:Question')->findBy(array(
            'trashed' => false,
        ), array(
            'created_at' => 'DESC',
        ), 4);
        $tutorials = $em->getRepository('ProtonTutorialBundle:Tutorial')->findBy(array(
            'trashed' => false,
        ), array(
            'created_at' => 'DESC',
        ), 4);

        return $this->render('ProtonFrontendBundle:Frontend:index.html.twig', array(
            'questions' => $questions,
            'tutorials' => $tutorials,
        ));
    }

}
