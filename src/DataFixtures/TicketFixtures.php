<?php

namespace App\DataFixtures;

use App\Factory\TicketFactory;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TicketFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $allUsers = UserFactory::repository()->findAll();
        foreach ($allUsers as $user) {
            $random = range(2, 10);
            TicketFactory::createMany($random[array_rand($random)], function () use ($user) {
                return [
                    'user' => $user,
                ];
            });
        }

        $user = UserFactory::repository()->findOneBy(['email' => 'root@example.com']);
        TicketFactory::createOne(
            [
                'user' => $user,
                'date' => new \DateTime('+1 day'),
            ]
        );
        TicketFactory::createOne(
            [
                'user' => $user,
                'date' => new \DateTime(),
            ]
        );

        $manager->flush();

    }
}
