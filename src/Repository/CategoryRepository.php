<?php


namespace App\Repository;


use App\Entity\Category;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class CategoryRepository
 * @package App\Repository
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * TeamRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param Event $event
     * @return Category[]
     */
    public function getAllCategoryFromEvent(Event $event)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.event = :eventID')
            ->setParameter('eventID', $event->getId());

        $query = $qb->getQuery();

        return $query->execute();
    }
}