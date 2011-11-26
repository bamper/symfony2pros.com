<?php

namespace Proton\TutorialBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Proton\TagBundle\Form\DataTransformer\TagTransformer;

class TutorialType extends AbstractType
{

    protected $tagTransformer;

    public function __construct(TagTransformer $tagTransformer = null)
    {
        $this->tagTransformer = $tagTransformer;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('tag_string')
            ->add('content')
        ;

        if (null !== $this->tagTransformer) {
            $builder->appendClientTransformer($this->tagTransformer);
        }
    }

    public function getName()
    {
        return 'proton_tutorialbundle_tutorialtype';
    }
}
