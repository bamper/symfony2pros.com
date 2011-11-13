<?php

namespace Proton\TutorialBundle\Creator;

use Proton\TutorialBundle\Model\TutorialInterface;
use Proton\TutorialBundle\Model\TutorialManagerInterface;
use Proton\TutorialBundle\Blamer\TutorialBlamerInterface;

class TutorialCreator implements TutorialCreatorInterface
{

    protected $tutorialManager;
    protected $blamer;

    public function __construct(TutorialManagerInterface $tutorialManager, TutorialBlamerInterface $blamer)
    {
        $this->tutorialManager = $tutorialManager;
        $this->blamer = $blamer;
    }

    public function create(TutorialInterface $tutorial)
    {
        $this->blamer->blame($tutorial);
        $this->tutorialManager->addTutorial($tutorial);

        return true;
    }

}
