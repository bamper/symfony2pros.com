<?php

namespace Proton\TutorialBundle\Entity;

use Proton\TutorialBundle\Model\TutorialManager as BaseTutorialManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Proton\TutorialBundle\Model\TutorialInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Proton\TutorialBundle\Model\DraftInterface;

class TutorialManager extends BaseTutorialManager
{

    protected $em;
    protected $redis;
    protected $repo;
    protected $class;

    public function __construct(EntityManager $em, \Predis\Client $redis, $class)
    {
        $this->em = $em;
        $this->redis = $redis;
        $this->repo = $em->getRepository($class);
        $this->class = $class;
    }

    /**
     * @return TutorialInterface
     */
    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function getTutorialList($limit = null)
    {
        $tutorials = $this->repo->findBy(array(
            'trashed' => false,
        ), array(
            'created_at' => 'DESC',
        ), $limit);

        return $tutorials;
    }

    public function addTutorial(TutorialInterface $tutorial)
    {
        $this->redis->hincrby(sprintf('user:%d', $tutorial->getAuthor()->getId()), 'tutorial_count', 1);
        $this->em->persist($tutorial);
        $this->em->flush();
    }

    public function updateTutorial(TutorialInterface $tutorial)
    {
        $this->em->persist($tutorial);
        $this->em->flush();
    }

    public function removeTutorial(TutorialInterface $tutorial)
    {
        $tutorial->setTrashed(true);
        $this->redis->hincrby(sprintf('user:%d', $tutorial->getAuthor()->getId()), 'tutorial_count', -1);
        $this->em->persist($tutorial);
        $this->em->flush();
    }

    public function getCommentCount(TutorialInterface $tutorial)
    {
        $threadRepo = $this->em->getRepository('ProtonCommentBundle:Thread');
        $thread = $threadRepo->findOneBy(array('id' => $tutorial->getSlug()));

        return $thread->getNumComments();
    }

    public function publishDraft(DraftInterface $draft)
    {
        $tutorial = $this->createTutorial();
        $tutorial->setAuthor($draft->getAuthor());
        $tutorial->setTitle($draft->getTitle());
        $tutorial->setDescription($draft->getDescription());
        $tutorial->setContent($draft->getContent());
        $this->em->remove($draft);
        $this->addTutorial($tutorial);

        return $tutorial;
    }

    public function getClass()
    {
        return $this->class;
    }

}
