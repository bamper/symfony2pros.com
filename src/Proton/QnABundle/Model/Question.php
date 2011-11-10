<?php

namespace Proton\QnABundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Question implements QuestionInterface
{

    protected $id;
    protected $created_at;
    protected $updated_at;
    protected $answers = array();
    protected $answer_count = 0;
    protected $author;
    protected $title;
    protected $slug;
    protected $content;
    protected $trashed = false;
    protected $views = 0;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    
    public function addAnswer(AnswerInterface $answer)
    {
        $this->answers[] = $answer;
    }
    
    public function getAnswers()
    {
        return $this->answers;
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
        $count = $this->getAnswerCount();
        $count += (int)$by;
        $this->setAnswerCount($count);

        return $count;
    }
    
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
    }
    
    public function getAuthor()
    {
        return $this->author;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setTrashed($trashed)
    {
        $this->trashed = $trashed;
    }

    public function isTrashed()
    {
        return $this->trashed;
    }

    public function setViews($views)
    {
        $this->views = $views;
    }

    public function getViews()
    {
        return $this->views;
    }

    public function incrementViews($by = 1)
    {
        $this->views += (int)$by;

        return $this->views;
    }

}
