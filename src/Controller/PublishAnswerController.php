<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PublishAnswerController extends AbstractController
{
    public function __invoke(Answer $data, Question $question): Answer
    {
        $data->setAuthor($this->getUser());
        $data->setCreatedAt(new \DateTimeImmutable());
        $data->setQuestion($question);

        return $data;
    }
}
