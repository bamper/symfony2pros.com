<?php

namespace Proton\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Comment as BaseComment;
use FOS\CommentBundle\Model\SignedCommentInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Comment extends BaseComment implements SignedCommentInterface
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Thread")
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="Proton\UserBundle\Entity\User")
     */
    protected $author;

    /**
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    public function setThread(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getAuthorName()
    {
        return $this->getAuthor() ? $this->getAuthor()->getUsername() : "Anonymous";
    }

}
