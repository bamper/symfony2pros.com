<?php

namespace Proton\QnABundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('content', 'textarea')
        ;
    }

    public function getName()
    {
        return 'proton_qnabundle_answertype';
    }
}
