<?php

namespace Proton\TutorialBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TutorialType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('content')
        ;
    }

    public function getName()
    {
        return 'proton_tutorialbundle_tutorialtype';
    }
}
