<?php

namespace Proton\TutorialBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Tutorial implements TutorialInterface
{

    protected $id;
    protected $created_at;
    protected $updated_at;
    protected $author;
    protected $title;
    protected $slug;
    protected $description;
    protected $content;
    protected $trashed = false;
    protected $views = 0;
    protected $status;

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

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
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

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

}
