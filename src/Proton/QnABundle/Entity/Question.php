<?php

namespace Proton\QnABundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Proton\QnABundle\Model\Question as BaseQuestion;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 */
class Question extends BaseQuestion
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $answers;

    /**
     * @ORM\Column(type="integer")
     */
    protected $answer_count = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Proton\UserBundle\Entity\User")
     */
    protected $author;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Gedmo\Slug(fields={"id", "title"})
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $trashed = false;

    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
