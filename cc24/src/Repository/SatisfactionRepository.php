<?php

namespace App\Repository;

use App\Entity\Atelier;
use App\Entity\Satisfaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Satisfaction>
 */
class SatisfactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Satisfaction::class);
    }

    //    /**
    //     * @return Satisfaction[] Returns an array of Satisfaction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Satisfaction
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    //
    public function findSatisfactionStatsByAtelier(Atelier $atelier): array
    {
        $qb = $this->createQueryBuilder('s')
            ->select('AVG(s.note) as average_note') // Calcul de la moyenne des notes
            ->where('s.atelier = :atelier')
            ->setParameter('atelier', $atelier)
            ->getQuery();


        $result = $qb->getSingleResult();

        // Si pas de résultats, retourner une moyenne de 0
        return [
            'average_note' => $result['average_note'] ?? 0
        ];
    }
    public function getNotesForAtelier(Atelier $atelier): array
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s.note')
            ->where('s.atelier = :atelier')
            ->setParameter('atelier', $atelier)
            ->getQuery();

        // On récupérer toutes les notes ici
        $result = $qb->getResult();


        return array_map(fn($item) => $item['note'], $result);
    }



}
