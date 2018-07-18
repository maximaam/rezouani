<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Category;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param array $categories
     * @return mixed
     */
    public function fetchByCategories(array $categories)
    {
        $qb = $this ->createQueryBuilder('p')
            ->leftJoin('p.category', 'c');
            //->leftJoin('p.images', 'i');

        //$qb->where($qb->expr()->in('c.id', $categories));

        $qb->where('c.id IN(:categories)')
            ->setParameter('categories', $categories);

        return $qb->getQuery()->getResult();
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
}
