<?php


namespace App\Controller;


use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlayersController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * PlayersController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/player", methods={"GET"}, name="playerPage")
     *
     */
    public function playerPage()
    {
        /** @var Player[] $players */
        $players = $this->em->getRepository(Player::class)->findBy(['owner' => '']);
        return $this->render('players/index.html.twig', [
            'players' => $players,
        ]);
    }
}