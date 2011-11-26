<?php

namespace Proton\TagBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use FPN\TagBundle\Entity\TagManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Proton\TutorialBundle\Model\TutorialInterface;

class TagTransformer implements DataTransformerInterface
{

    protected $tagManager;

    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof TutorialInterface) {
            throw new UnexpectedTypeException($value, 'Proton\TutorialBundle\Model\TutorialInterface');
        }

        $this->tagManager->loadTagging($value);

        $tagString = array();
        foreach ($value->getTags() as $tag) {
            $tagString[] = $tag->getSlug();
        }
        $value->tag_string = implode(' ', $tagString);

        return $value;
    }

    public function reverseTransform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof TutorialInterface) {
            throw new UnexpectedTypeException($value, 'Proton\TutorialBundle\Model\TutorialInterface');
        }

        $tagNames = $this->tagManager->splitTagNames($value->tag_string, ' ');
        $tags = $this->tagManager->loadOrCreateTags($tagNames);
        $this->tagManager->replaceTags($tags, $value);
        $this->tagManager->saveTagging($value);

        return $value;
    }

}
