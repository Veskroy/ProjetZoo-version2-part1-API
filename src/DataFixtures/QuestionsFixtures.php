<?php

namespace App\DataFixtures;

use App\Factory\QuestionFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuestionsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $allUsers = UserFactory::repository()->findAll();
        foreach ($allUsers as $user) {
            QuestionFactory::createMany(10, function () use ($user) {
                return [
                    'author' => $user,
                ];
            });
        }
        $manager->flush();
    }

    public function getDependencies(): array {
        return [
            UserFixtures::class,
        ];
    }
}
