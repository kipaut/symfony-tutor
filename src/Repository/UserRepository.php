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
     * @return User[]
     */
    public function findAllEmailAlphabetical()
    {
        return $this->createQueryBuilder('user')
            ->orderBy('user.email', 'ASC')
            ->getQuery()
            ->execute();
    }

    /**
     * @return User[]
     */
    public function findByNames(string $query, int $limit = 5)
    {
        return $this->getByNamesQueryBuilder($query, $limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param null|string $term
     * @param int|null $limit
     * @return QueryBuilder
     */
    public function getByNamesQueryBuilder(?string $term, int $limit = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('user');

        if ($term) {
            $qb->andWhere(
                'user.email LIKE :term OR 
                 user.firstName LIKE :term OR 
                 user.lastName LIKE :term'
            )->setParameter('term', '%'.$term.'%');
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->orderBy('user.createdAt', 'DESC');
    }
}
