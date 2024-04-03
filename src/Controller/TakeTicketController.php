<?php

namespace App\Controller;

use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class TakeTicketController extends AbstractController
{
    public function __invoke(Security $security, EntityManagerInterface $entityManager, Ticket $data): Ticket
    {
        $data->setUser($security->getUser());
        $data->setDate(new \DateTimeImmutable());
        $entityManager->persist($data);
        $entityManager->flush();

        return $data;
    }
}
