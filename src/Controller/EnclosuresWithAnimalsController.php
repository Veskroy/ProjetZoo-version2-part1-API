<?php

namespace App\Controller;

use App\Entity\Pen;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class EnclosuresWithAnimalsController  extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function listEnclosuresWithAnimals(): JsonResponse
    {
        $pens = $this->entityManager->getRepository(Pen::class)->findAll();
        $enclosuresWithAnimals = [];
        foreach ($pens as $pen) {
            $penId = $pen->getId();
            $penType = $pen->getType();
            $animals = [];
            foreach ($pen->getAnimal() as $animal) {
                $animals[] = [
                    'id' => $animal->getId(),
                    'name' => $animal->getName(),
                ];
            }

            $enclosuresWithAnimals[] = [
                'pen_id' => $penId,
                'pen_type' => $penType,
                'animals' => $animals,
            ];
        }
        return $this->json($enclosuresWithAnimals);
    }
}