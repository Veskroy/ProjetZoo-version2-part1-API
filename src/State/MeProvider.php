<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class MeProvider implements ProviderInterface
{
    public User $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // Retrieve the state from somewhere
        return $this->user;
    }
}
