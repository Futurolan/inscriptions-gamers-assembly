<?php


namespace App\Repository;


use App\Entity\Player;
use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class PlayerRepository
 * @package App\Repository
 */
class PlayerRepository extends ServiceEntityRepository
{
    /**
     * TeamRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * @param Tournament $tournament
     * @return Player[]
     */
    public function getAllPlayersFromTournament(Tournament $tournament)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.tournament = :tournamentID')
            ->setParameter('tournamentID', $tournament->getId());

        $query = $qb->getQuery();

        return $query->execute();
    }
}