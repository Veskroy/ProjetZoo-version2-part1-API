<?php

namespace App\DataFixtures;

use App\Factory\PenFactory;
use App\Factory\SpotFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PenFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        PenFactory::createMany(15, function () {
            return [
                'spot' => SpotFactory::random(),
            ];
        });
        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [
            SpotFixtures::class,
        ];
    }
}
