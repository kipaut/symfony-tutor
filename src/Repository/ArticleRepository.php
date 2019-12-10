<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[]
     */
    public function findAllPublishedOrderedByNewest()
    {
        return $this->addIsPublishedQueryBuilder()
            ->leftJoin('article.tags', 'tags')
            ->addSelect('tags')
            ->orderBy('article.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    private function addIsPublishedQueryBuilder(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('article.publishedAt IS NOT NULL');
    }

    /**
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->createQueryBuilder('article');
    }

    /**
     * @param null|string $term
     * @param int|null $limit
     * @return QueryBuilder
     */
    public function getByTitleQueryBuilder(?string $term, int $limit = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('article');

        if ($term) {
            $qb->andWhere(
                'article.title LIKE :term'
            )->setParameter('term', '%'.$term.'%');
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->orderBy('article.createdAt', 'DESC');
    }
}
