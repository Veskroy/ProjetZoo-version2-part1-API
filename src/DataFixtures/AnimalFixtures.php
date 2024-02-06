<?php

namespace App\DataFixtures;

use App\Factory\AnimalFactory;
use App\Factory\PenFactory;
use App\Factory\SpeciesFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AnimalFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        AnimalFactory::createMany(30, function () {
            return [
                'species' => SpeciesFactory::random(),
                'pen' => PenFactory::random()
            ];
        });
    }

    public function getDependencies(): array
    {
        return [
            SpeciesFixtures::class,
            PenFixtures::class,
        ];
    }

}
