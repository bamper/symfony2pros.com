<?php

namespace Proton\TutorialBundle\Blamer;

use Proton\TutorialBundle\Model\TutorialInterface;

interface TutorialBlamerInterface
{

    function blame(TutorialInterface $tutorial);

}
