<?php

namespace Proton\TutorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Proton\TutorialBundle\Model\Tutorial as BaseTutorial;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 */
class Tutorial extends BaseTutorial
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
     * @ORM\ManyToOne(targetEntity="Proton\UserBundle\Entity\User")
     */
    protected $author;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @Gedmo\Slug(fields={"id","title"})
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

}
