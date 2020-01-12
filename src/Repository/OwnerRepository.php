<?php


namespace App\Repository;


use App\Entity\Owner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class OwnerRepository
 * @package App\Repository
 */
class OwnerRepository extends ServiceEntityRepository
{
    /**
     * TeamRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Owner::class);
    }

    /**
     * @return Owner[]
     */
    public function findAll()
    {
        return $this->findBy(array(), array('firstname' => 'ASC'));
    }
}