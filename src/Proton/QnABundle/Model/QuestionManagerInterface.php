<?php

namespace Proton\QnABundle\Model;

interface QuestionManagerInterface
{

    function getClass();

    /**
     * @return QuestionInterface
     */
    function createQuestion();

}
