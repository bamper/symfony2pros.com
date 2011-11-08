<?php

namespace Proton\TutorialBundle\Model;

interface TutorialManagerInterface
{

    function getClass();

    /**
     * @return TutorialInterface
     */
    function createTutorial();

}
