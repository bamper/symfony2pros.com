<?php

namespace Proton\TagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FPN\TagBundle\Entity\TagManager;
use DoctrineExtensions\Taggable\Taggable;
use Proton\TagBundle\Entity\Tag;

class TagController extends Controller
{

    public function displayAction(Taggable $entity)
    {
        $this->getTagManager()->loadTagging($entity);

        return $this->render('ProtonTagBundle:Tag:display.html.twig', array(
            'tags' => $entity->getTags(),
        ));
    }

    /**
     * @return TagManager
     */
    protected function getTagManager()
    {
        return $this->container->get('fpn_tag.tag_manager');
    }

}
