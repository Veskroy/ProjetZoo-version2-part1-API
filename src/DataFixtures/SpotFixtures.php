<?php

namespace App\DataFixtures;

use App\Factory\SpotFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SpotFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $file = file_get_contents(__DIR__.'/data/Spot.json');
        $array = json_decode($file, true);
        SpotFactory::createSequence($array);

        $manager->flush();
    }
}
