<?php

namespace App\Repository;

use App\Entity\HorseCandidate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class HorseCandidateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HorseCandidate::class);
    }

    public function findBestCandidateEver():? HorseCandidate
    {
        $qb = $this->createQueryBuilder('hc');
        $qb->setMaxResults(1);
        $qb->andWhere('hc.finishedAt > 0');
        $qb->orderBy('hc.time', 'ASC');

        $candidate = $qb->getQuery()->getResult();

        return count($candidate) ? array_shift($candidate) : null;
    }
}
