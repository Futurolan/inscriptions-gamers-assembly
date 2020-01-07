<?php


namespace App\Controller;


use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
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
     * pastor.pierre2@outlook.fr
     * david.thimeur@hotmail.fr
     * teamneverbackdown.esport@gmail.com
     */
    public function playerPage()
    {
        /** @var Player[] $players */
        $players = $this->em->getRepository(Player::class)->findBy(['owner' => 'teamneverbackdown.esport@gmail.com']);
        return $this->render('players/index.html.twig', [
            'players' => $players,
        ]);
    }
}