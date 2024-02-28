<?php

namespace App\Controller;

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditQuestionController extends AbstractController
{
    public function __invoke(Question $data): Question
    {
        $data->setUpdatedAt(new \DateTimeImmutable());

        return $data;
    }
}
