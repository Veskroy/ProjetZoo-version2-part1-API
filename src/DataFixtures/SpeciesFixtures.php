<?php

namespace App\DataFixtures;

use App\Factory\FamilyFactory;
use App\Factory\SpeciesFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SpeciesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        SpeciesFactory::createMany(15, function () {
            return [
                'family' => FamilyFactory::random(),
            ];
        });
    }

    public function getDependencies(): array
    {
        return [
            FamilyFixtures::class,
        ];
    }

}
