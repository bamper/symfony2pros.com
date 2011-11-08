<?php

namespace Proton\QnABundle\Model;

abstract class QuestionManager implements QuestionManagerInterface
{

    public function createQuestion()
    {
        $class = $this->getClass();
        $question = new $class();

        return $question;
    }

}
