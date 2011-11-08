<?php

namespace Proton\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 */
class User extends BaseUser
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $tutorial_count = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $question_count = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $answer_count = 0;

    public function setTutorialCount($tutorial_count)
    {
        $this->tutorial_count = (int)$tutorial_count;
    }

    public function getTutorialCount()
    {
        return $this->tutorial_count;
    }

    public function incrementTutorialCount($by = 1)
    {
        $count = $this->tutorial_count;
        $count += (int)$by;
        $this->tutorial_count = $count;

        return $count;
    }

    public function setQuestionCount($question_count)
    {
        $this->question_count = (int)$question_count;
    }

    public function getQuestionCount()
    {
        return $this->question_count;
    }

    public function incrementQuestionCount($by = 1)
    {
        $count = $this->question_count;
        $count += (int)$by;
        $this->question_count = $count;

        return $count;
    }

    public function setAnswerCount($answer_count)
    {
        $this->answer_count = (int)$answer_count;
    }

    public function getAnswerCount()
    {
        return $this->answer_count;
    }

    public function incrementAnswerCount($by = 1)
    {
        $count = $this->answer_count;
        $count += (int)$by;
        $this->answer_count = $count;

        return $count;
    }

}
