<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::CreateOne([
            'firstname' => 'Logan',
            'lastname' => 'Jacotin',
            'email' => 'root@example.com',
            'password' => 'test',
            'roles' => ['ROLE_ADMIN'],
        ]);
        UserFactory::CreateOne([
            'firstname' => 'Romain',
            'lastname' => 'Leroy',
            'email' => 'user@example.com',
            'password' => 'test',
            'roles' => ['ROLE_USER'],
        ]);
        UserFactory::CreateOne([
            'firstname' => 'Clément',
            'lastname' => 'Perrot',
            'email' => 'employe@example.com',
            'password' => 'test',
            'roles' => ['ROLE_EMPLOYEE', 'ROLE_USER'],
        ]);
        UserFactory::createMany(10);
    }
}
