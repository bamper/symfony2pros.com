<?php

namespace Proton\QnABundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
        ;
    }

    public function getName()
    {
        return 'proton_qnabundle_questiontype';
    }
}
