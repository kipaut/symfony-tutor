<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param null|string $term
     * @return QueryBuilder
     */
    public function getWithSearchQueryBuilder(?string $term): QueryBuilder
    {
        $qb = $this->createQueryBuilder('user');

        if ($term) {
            $qb->andWhere(
                'user.email LIKE :term OR 
                 user.firstName LIKE :term OR 
                 user.lastName LIKE :term OR 
                 user.twitterUsername LIKE :term OR'
            )->setParameter('term', '%' . $term . '%');
        }

        return $qb->orderBy('user.createdAt', 'DESC');
    }
}
