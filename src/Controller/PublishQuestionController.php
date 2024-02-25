<?php

namespace App\Controller;

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PublishQuestionController extends AbstractController
{
    public function __invoke(Question $data): Question
    {
        $data->setAuthor($this->getUser());
        return $data;
    }
}
