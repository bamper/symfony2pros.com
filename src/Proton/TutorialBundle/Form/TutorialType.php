<?php

namespace Proton\TutorialBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TutorialType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title');
        if (true === $options['show_status']) {
            $builder->add('status', 'choice', array(
                'choices' => array(
                    'draft'     => 'Draft',
                    'published' => 'Publish',
                ),
            ));
        }
        $builder
            ->add('description')
            ->add('content')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'show_status' => true,
        );
    }

    public function getName()
    {
        return 'proton_tutorialbundle_tutorialtype';
    }
}
