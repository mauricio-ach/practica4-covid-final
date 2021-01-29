<?php

namespace App\Repository;

use App\Entity\Enlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Enlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enlace[]    findAll()
 * @method Enlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enlace::class);
    }

    public function findOneByValues($user,$contact) {
        try {
            return $this->createQueryBuilder('e')
                ->where('e.idUsuario = :val1')
                ->setParameter('val1', $user)
                ->andWhere('e.idContacto = :val2')
                ->setParameter('val2',$contact)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    // /**
    //  * @return Enlace[] Returns an array of Enlace objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Enlace
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
