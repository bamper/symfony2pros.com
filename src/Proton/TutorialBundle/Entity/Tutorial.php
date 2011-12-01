<?php

namespace Proton\TutorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Proton\TutorialBundle\Model\Tutorial as BaseTutorial;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\Taggable\Taggable;

/**
 * @ORM\Entity
 */
class Tutorial extends BaseTutorial implements Taggable
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
    protected $author_name;

    /**
     * @ORM\Column(type="string")
     */
    protected $author_email;

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

    /**
     * @ORM\Column(type="boolean")
     */
    protected $trashed = false;

    /**
     * @ORM\Column(type="integer")
     */
    protected $views = 1;

    protected $tags;

    public $tag_string;

    public function getTaggableId()
    {
        return $this->id;
    }

    public function getTaggableType()
    {
        return 'proton_tutorial';
    }

    public function getTags()
    {
        $this->tags = $this->tags ?: new \Doctrine\Common\Collections\ArrayCollection();

        return $this->tags;
    }

}
