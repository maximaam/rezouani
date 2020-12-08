<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Category;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param array $categories
     * @return QueryBuilder
     */
    public function fetchByCategories(array $categories): QueryBuilder
    {
        $qb = $this ->createQueryBuilder('p')
            ->leftJoin('p.category', 'c');
            //->leftJoin('p.images', 'i');

        //$qb->where($qb->expr()->in('c.id', $categories));

        $qb->where('c.id IN(:categories)')
            ->setParameter('categories', $categories);

        return $qb;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids)
    {
        $q = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $ids);

        return $q->getQuery()->getResult();
    }

    /**
     * @param string $query
     * @param string $locale
     * @return mixed
     */
    public function searchTitle(string $query, string $locale)
    {
        $q = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.title' . \ucfirst($locale) . ' LIKE :title')
            ->setParameter('title', '%' . $query . '%');

        return $q->getQuery()->getResult();
    }
}
