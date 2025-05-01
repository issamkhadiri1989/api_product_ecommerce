<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Retrieves all products from the database. It is equivalent to the findAll() method.
     * I tend to use my own methods to retrieve data using Doctrine because I don't really feel comfortable using find*.
     *
     * @return Product[]
     */
    public function retrieveProducts(): array
    {
        return $this->createQueryBuilder('product')
            ->orderBy('product.id', 'desc')
            ->getQuery()
            ->getArrayResult();
    }

    public function loadProducts(array $identifiers): array
    {
        $queryBuilder = $this->createQueryBuilder('product');

        $expr = $queryBuilder->expr();

        return $queryBuilder
            ->where($expr->in('product.id', ':ids'))
            ->setParameter('ids', $identifiers)
            ->getQuery()->getResult();
    }
}
