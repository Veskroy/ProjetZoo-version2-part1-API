<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function getAllQuestionsOrderByDateDesc(): Query
    {
        return $this->createQueryBuilder('q')
            ->orderBy('q.updatedAt', 'DESC')
            ->getQuery();
    }

    public function search(string $text = ''): array
    {
        return $this->createQueryBuilder('q')
            ->where('q.title LIKE :text')
            ->orWhere('q.description LIKE :text')
            ->setParameter('text', '%' . $text . '%')
            ->orderBy('q.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findLikedQuestions(User $user): Query
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.likes', 'u')
            ->where('u.id = :user')
            ->setParameter('user', $user)
            ->orderBy('q.updatedAt', 'DESC')
            ->getQuery();
    }

//    /**
//     * @return Question[] Returns an array of Question objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Question
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
