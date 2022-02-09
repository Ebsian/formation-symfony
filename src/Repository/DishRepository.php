<?php

namespace App\Repository;

use App\DTO\SearchDishCriteria;
use App\Entity\Dish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dish[]    findAll()
 * @method Dish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dish::class);
    }

    public function findByCriteria(SearchDishCriteria $criteria) : array 
    {
        $queryBuilder = $this->createQueryBuilder('dish');

        if ($criteria->title) {
            $queryBuilder
                ->andWhere('dish.name LIKE :title')
                ->setParameter('title', '%'. $criteria->title . '%');
        }
        
        return $queryBuilder
            ->setMaxResults($criteria->limit)
            ->setFirstResult($criteria->limit * ($criteria->page - 1))
            ->getQuery()
            ->getResult();
    }

    public function findAllOrderedByPrice (): array
    {
        return $this
            ->createQueryBuilder('dish')
            ->orderBy('dish.price', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    public function findAllTypePizza () : array
    {
        return $this
            ->createQueryBuilder('dish')
            ->andWhere('dish.type LIKE :like')
            ->setParameter('like', 'pizza')
            ->getQuery()
            ->getResult();
    }

    public function findFiveOrderedName () : array
    {
        return $this
            ->createQueryBuilder('dish')
            ->orderBy('dish.name', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findTenOfPageTwoOrderedByPrice () : array
    {
        return $this
            ->createQueryBuilder('dish')
            ->orderBy('dish.price', 'DESC')
            ->setMaxResults(10)
            ->setFirstResult(11)
            ->getQuery()
            ->getResult();
    }

    public function findByTomato () : array
    {
        return $this
            ->createQueryBuilder('dish')
            ->setMaxResults(15)
            ->leftJoin('dish.ingredients', 'ingredient')
            ->andWhere('ingredient.name = :ingredientName')
            ->setParameter('ingredientName', 'tomate')
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Dish[] Returns an array of Dish objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dish
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
