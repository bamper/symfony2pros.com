<?php

namespace Proton\QnABundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Question implements QuestionInterface
{

    protected $id;
    protected $created_at;
    protected $updated_at;
    protected $answers = array();
    protected $author;
    protected $title;
    protected $slug;
    protected $content;
    
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

}
