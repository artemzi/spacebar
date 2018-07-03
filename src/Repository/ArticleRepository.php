<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findAllPublishedOrderedByNewest()
    {
        return $this->addIsPublishedQueryBuilder()
            ->orderBy('a.published_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function addIsPublishedQueryBuilder(QueryBuilder $qb=null)
    {
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('a.published_at IS NOT NULL');
    }

    public function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->createQueryBuilder('a');
    }
}
