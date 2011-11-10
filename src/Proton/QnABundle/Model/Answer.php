<?php

namespace Proton\QnABundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Answer implements AnswerInterface
{

    protected $id;
    protected $created_at;
    protected $updated_at;
    protected $question;
    protected $author;
    protected $content;
    protected $trashed;

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

    public function setQuestion(QuestionInterface $question)
    {
        $this->question = $question;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
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

}
