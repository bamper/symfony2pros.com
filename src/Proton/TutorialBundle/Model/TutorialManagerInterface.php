<?php

namespace Proton\TutorialBundle\Model;

interface TutorialManagerInterface
{

    function getClass();

    /**
     * @return TutorialInterface
     */
    function createTutorial();

    /**
     * @return TutorialInterface
     */
    function find($id);

    function getTutorialList($limit = null);

    function addTutorial(TutorialInterface $tutorial);

    function updateTutorial(TutorialInterface $tutorial);

    function removeTutorial(TutorialInterface $tutorial);


}
