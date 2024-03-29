<?php

namespace Proton\QnABundle\Entity;

use Proton\QnABundle\Model\QuestionManager as BaseQuestionManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Proton\QnABundle\Model\QuestionInterface;

class QuestionManager extends BaseQuestionManager
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

    public function addQuestion(QuestionInterface $question)
    {
        $question->getAuthor()->incrementQuestionCount();
        $this->em->persist($question);
        $this->em->persist($question->getAuthor());
        $this->em->flush();
    }

    public function getClass()
    {
        return $this->class;
    }

}
