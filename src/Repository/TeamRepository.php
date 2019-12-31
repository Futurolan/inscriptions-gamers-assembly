<?php


namespace App\Repository;


use App\Entity\Team;
use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class TeamRepository
 * @package App\Repository
 */
class TeamRepository extends ServiceEntityRepository
{
    /**
     * TeamRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * @param Tournament $tournament
     * @return Team[]
     */
    public function getAllTeamFromTournament(Tournament $tournament)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.tournament = :tournamentID')
            ->setParameter('tournamentID', $tournament->getId())
            ->orderBy('t.team_name', 'ASC');

        $query = $qb->getQuery();

        return $query->execute();
    }
}