<?php

namespace Proton\TutorialBundle\Entity;

use Proton\TutorialBundle\Model\TutorialManager as BaseTutorialManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Proton\TutorialBundle\Model\TutorialInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
            'status' => 'published',
        ), array(
            'created_at' => 'DESC',
        ), $limit);

        return $tutorials;
    }

    public function findDraftsByAuthor(UserInterface $user)
    {
        $tutorials = $this->repo->findBy(array(
            'author' => $user,
            'status' => 'draft',
            'trashed' => false,
        ), array(
            'created_at' => 'DESC',
        ));

        return $tutorials;
    }

    public function addTutorial(TutorialInterface $tutorial)
    {
        $tutorial->getAuthor()->incrementTutorialCount();
        $this->em->persist($tutorial);
        $this->em->persist($tutorial->getAuthor());
        $this->em->flush();
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
