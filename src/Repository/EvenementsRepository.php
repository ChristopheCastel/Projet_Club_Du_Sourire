<?php

namespace App\Repository;

use App\Entity\Evenements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenements[]    findAll()
 * @method Evenements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenements::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Evenements $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Evenements $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function prochainsEvenements()
    {
        
        $DateObjetNow = new \DateTime("now");

            $dateDuJour = $DateObjetNow;
       

        // dd($dateDuJour);//Date du jour

        return $this->createQueryBuilder("e")
            ->andwhere("e.date > :dateDuJour")
            ->setParameter("dateDuJour", $dateDuJour)
            ->orderBy("e.date", "ASC")
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
            ;
            
    }

    public function findCategorieAsc($arg)
    {
        return $this->createQueryBuilder("e")
            ->join("e.categorie", "c") // table categorie sous l'alias c
            ->andWhere("c.id IN(:cat)")
            ->setParameter("cat", $arg)
            ->orderBy("e.date", "ASC")
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllAsc()
    {
        return $this->createQueryBuilder("e")
            ->orderBy("e.date", "ASC")
            ->getQuery()
            ->getResult()
        ;
    }

    

    // /**
    //  * @return Evenements[] Returns an array of Evenements objects
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
    public function findOneBySomeField($value): ?Evenements
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
