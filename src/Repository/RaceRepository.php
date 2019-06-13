<?php

namespace App\Repository;

use App\Entity\HorseCandidate;
use App\Entity\Race;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Race::class);
    }

    public function getCountByNotFinished(): int
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('COUNT(c.id)');
        $qb->setMaxResults(1);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function findNotFinishedRaces(): array
    {
        return $this->findBy(['finished' => false]);
    }

    public function findFinishedRaces(int $limit): array
    {
        return $this->findBy(['finished' => true], null, $limit);
    }

    public function findBestCandidateEver():? HorseCandidate
    {
        return $this->_em->getRepository(HorseCandidate::class)->findBestCandidateEver();
    }

    public function save(Race $race)
    {
        $this->_em->persist($race);
        $this->_em->flush();
    }
}
