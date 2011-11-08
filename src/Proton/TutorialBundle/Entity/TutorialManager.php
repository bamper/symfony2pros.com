<?php

namespace Proton\TutorialBundle\Entity;

use Proton\TutorialBundle\Model\TutorialManager as BaseTutorialManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Proton\TutorialBundle\Model\TutorialInterface;

class TutorialManager extends BaseTutorialManager
{

    protected $em;
    protected $repo;
    protected $class;

    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repo = $em->getRepository($class);
        $this->class = $class;
    }

    public function getCommentCount(TutorialInterface $tutorial)
    {
        $threadRepo = $this->em->getRepository('ProtonCommentBundle:Thread');
        $thread = $threadRepo->findOneBy(array('id' => $tutorial->getSlug()));

        return $thread->getNumComments();
    }

    public function getClass()
    {
        return $this->class;
    }

}
