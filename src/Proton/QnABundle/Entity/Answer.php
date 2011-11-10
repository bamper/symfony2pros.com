<?php

namespace Proton\QnABundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Proton\QnABundle\Model\Answer as BaseAnswer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 */
class Answer extends BaseAnswer
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
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="answers")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $question;

    /**
     * @ORM\ManyToOne(targetEntity="Proton\UserBundle\Entity\User")
     */
    protected $author;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $trashed = false;

}
