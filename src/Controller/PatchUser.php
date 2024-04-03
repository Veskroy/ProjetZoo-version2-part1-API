<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class PatchUser extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @throws \Exception
     */
    public function __invoke($data, EntityManagerInterface $entityManager): JsonResponse
    {
        $currentUser = $this->security->getUser();

        if (!$currentUser instanceof User) {
            throw new \Exception('User not found');
        }

        $currentUser->setFirstname($data->getFirstName());
        $currentUser->setLastname($data->getLastName());
        $currentUser->setAddress($data->getAddress());
        $currentUser->setCity($data->getCity());
        $currentUser->setPc($data->getPc());
        $currentUser->setPhone($data->getPhone());

        $entityManager->flush();

        return new JsonResponse(['message' => 'User updated successfully'], JsonResponse::HTTP_OK);
    }
}
