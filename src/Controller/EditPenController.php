<?php

namespace App\Controller;

use App\Entity\Pen;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EditPenController extends AbstractController
{
    public function __invoke(Pen $data, EntityManagerInterface $entityManager, Request $request): Pen
    {
        $data->setType($data->getType());
        $data->setCapacity($data->getCapacity());
        $data->setSize($data->getSize());
        $entityManager->persist($data);
        $entityManager->flush();

        return $data;
    }
}