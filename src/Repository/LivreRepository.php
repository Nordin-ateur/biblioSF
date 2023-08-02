<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

   /**
    * @return Livre[] Retourne un tableau d'objet Livre
    SELECT l.*
    FROM livre l
    WHERE l.titre LIKE :val OR l.resume LIKE :val        
    ORDER BY l.titre
    // LIMIT 10

    :val = "%$value%"
    */
   public function findByRecherche($value): array
   {
       return $this->createQueryBuilder('l')
           ->where('l.titre LIKE :val')
           ->orWhere('l.resume LIKE :val')
           ->setParameter('val', "%$value%")
           ->orderBy('l.titre', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
