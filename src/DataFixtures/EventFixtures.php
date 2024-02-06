<?php

namespace App\DataFixtures;

use App\Factory\EventFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $file = file_get_contents(__DIR__.'/data/Events.json');
        $array = json_decode($file, true);
        EventFactory::createSequence($array);
        EventFactory::createMany(3);

        $manager->flush();
    }
}
