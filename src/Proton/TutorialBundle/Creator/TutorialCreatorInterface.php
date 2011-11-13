<?php

namespace Proton\TutorialBundle\Creator;

use Proton\TutorialBundle\Model\TutorialInterface;

interface TutorialCreatorInterface
{

    function create(TutorialInterface $tutorial);

}
