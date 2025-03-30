<?php

namespace App\Repository;

use App\Entity\Recapitulatif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recapitulatif>
 */
class RecapitulatifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recapitulatif::class);
    }
    public function findByUser($user)
    {
        return $this->createQueryBuilder('s')
            ->join('s.order', 'o')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

}
