<?php

namespace App\Controller;

use App\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditAnswerController extends AbstractController
{
    public function __invoke(Answer $data): Answer
    {
        $data->setUpdatedAt(new \DateTimeImmutable());
        return $data;
    }
}
