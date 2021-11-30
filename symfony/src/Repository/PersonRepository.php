<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findByName($value)
    {
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->gte('p.name', '?1'))
            ->setParameter(1, $value)
            ->getQuery()
            ->getResult();
    }

    public function findByAge($value)
    {
        $arr = explode(',', $value);
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->gte('p.age', '?1'))
            ->andWhere($builder->expr()->lte('p.age', '?2'))
            ->setParameters(array(1 => $arr[0], 2 => $arr[1]))
            ->getQuery()
            ->getResult();
    }

    public function findByNameOrEmail($value)
    {
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->where($builder->expr()->like('p.name', '?1'))
            ->orWhere($builder->expr()->like('p.email', '?2'))
            ->setParameters(
                array(
                    1 => '%'. $value . '%',
                    2 => '%'. $value . '%'
                )
            )
            ->getQuery()
            ->getResult();
    }

    public function findAllWithSort()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.age', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Person[] Returns an array of Person objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Person
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
