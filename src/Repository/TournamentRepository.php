<?php


namespace App\Repository;


use App\Entity\Category;
use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TournamentRepository extends ServiceEntityRepository
{
    /**
     * TeamRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournament::class);
    }

    /**
     * @param Category $category
     * @return Tournament[]
     */
    public function getAllTournamentFromCategory(Category $category)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.category = :categoryID')
            ->setParameter('categoryID', $category->getId());

        $query = $qb->getQuery();

        return $query->execute();
    }
}