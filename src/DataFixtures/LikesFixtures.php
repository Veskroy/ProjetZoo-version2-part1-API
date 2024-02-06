<?php

namespace App\DataFixtures;

use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LikesFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly UserRepository $userRepository, private readonly QuestionRepository $questionRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        /*$allUsers = UserRepository::class->findAll();
        $questions = QuestionRepository::class->findAll();*/

        $allUsers = $this->userRepository->findAll();
        $questions = $this->questionRepository->findAll();

        foreach ($questions as $question) {
            for ($i = 0; $i < mt_rand(0, 15); ++$i) {
                $question->addLike(
                    $allUsers[mt_rand(0, count($allUsers) - 1)]
                );
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            QuestionsFixtures::class,
        ];
    }
}
