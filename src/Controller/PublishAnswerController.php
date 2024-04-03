<?php

namespace App\Controller;

use App\Entity\Answer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class PublishAnswerController extends AbstractController
{
    public function __invoke(Security $security, EntityManagerInterface $entityManager, Answer $data): Answer
    {
        $data->setAuthor($this->getUser());
        $data->setCreatedAt(new \DateTimeImmutable());
        $entityManager->persist($data);
        $entityManager->flush();

        return $data;
    }
}
