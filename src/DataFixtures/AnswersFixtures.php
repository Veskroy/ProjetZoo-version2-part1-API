<?php

namespace App\DataFixtures;

use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AnswersFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //AnswerFactory::createMany(10);
        $allUsers = UserFactory::repository()->findAll();

        if (!empty($allUsers)) {
            $allQuestions = QuestionFactory::repository()->findAll();

            foreach ($allQuestions as $question) {
                $random = range(0, 10);
                AnswerFactory::createMany($random[array_rand($random)], function () use ($question) {
                    return [
                        'author' => UserFactory::random(),
                        'question' => $question,
                    ];
                });
            }

            $manager->flush();
        }
        else {
            throw new \Exception("Aucun utilisateur trouvé");
        }
    }

    public function getDependencies(): array {
        return [
            QuestionsFixtures::class,
        ];
    }
}
