<?php

namespace App\DataFixtures;

use App\Factory\FamilyFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FamilyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $file = file_get_contents(__DIR__.'/data/Family.json');
        $array = json_decode($file, true);
        FamilyFactory::createSequence($array);

        $manager->flush();
    }
}
