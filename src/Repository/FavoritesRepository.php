<?php
namespace App\Repository;

use App\Entity\Favorites;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FavoritesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favorites::class);
    }

    /**
     * Récupère tous les products favoris d'un user donné.
     */
    public function findFavoritesByUser(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.user = :user')
            ->setParameter('user', $user)
            ->join('f.product', 'p')
            ->addSelect('p')
            ->getQuery()
            ->getResult();
    }
}
