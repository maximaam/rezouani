<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function fetchParents()
    {
        return $this->createQueryBuilder('c')
            ->where('c.parent IS NULL')
            ->orderBy('c.id', 'ASC')
            //->setMaxResults(5)
        ;
    }

    /**
     * @param Category|null $category
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function fetchChildren(Category $category = null)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.parent IS NOT NULL');

        if (null !== $category) {
            $qb->andWhere('c.parent = :parentId')
                ->setParameter('parentId', $category->getId());
        }

        $qb->orderBy('c.id', 'ASC');

        return $qb;
    }


    /**
     * @param Category $category
     * @return array
     */
    public static function getRelatedCategories(Category $category): array
    {
        return array_merge(
            self::getAllChildren($category),
            self::getAllParents($category)
        );
    }

    /**
     * @param Category $category
     * @return array
     */
    private static function getAllChildren(Category $category)
    {
        static $categories = [];

        $categories[] = $category->getId();

        if (!$category->getChildren()->isEmpty()) {

            foreach ($category->getChildren() as $child) {
                self::getAllChildren($child);
            }
        }

        return $categories;
    }

    /**
     * @param Category $category
     * @return array
     */
    private static function getAllParents(Category $category)
    {
        static $categories = [];

        if ($category->getParent()) {
            $categories[] = $category->getParent()->getId();

            self::getAllParents($category->getParent());
        }

        return $categories;
    }


    /**
     * Get children IDs
     *
     * @param Category $category
     * @return array
     */
    public static function getChildrenIds(Category $category): array
    {
        /** @var Category $cat */
        return array_map(function ($cat) {
            return $cat->getId();
        }, iterator_to_array($category->getChildren()));
    }
}
