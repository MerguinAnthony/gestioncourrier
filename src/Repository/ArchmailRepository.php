<?php

namespace App\Repository;

use App\Entity\Archmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Archmail>
 *
 * @method Archmail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Archmail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Archmail[]    findAll()
 * @method Archmail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchmailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Archmail::class);
    }

    //    /**
    //     * @return Archmail[] Returns an array of Archmail objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Archmail
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
