<?php


namespace App\Controller;


use App\Entity\Owner;
use App\Entity\Player;
use App\Form\PlayerFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     * @Route("/player/{password}", methods={"GET"}, name="playerPage")
     * @param string $password
     * @return Response
     */
    public function playerPage(string $password)
    {
        $owner = $this->getOwner($password);

        /** @var Player[] $players */
        $players = $this->em->getRepository(Player::class)->findBy(['owner' => $owner->getEmail()]);
        return $this->render('players/index.html.twig', [
            'players' => $players,
            'require' => true,
            'password' => $password,
        ]);
    }

    /**
     * @Route("/player/{password}/modify/{player}", methods={"GET"}, name="playerFormPage")
     * @param string $password
     * @param Player $player
     * @return Response
     */
    public function playerFormPage(string $password, Player $player)
    {
        $owner = $this->getOwner($password);
        if ( $player->getOwner() !== $owner->getEmail() ) { throw new AccessDeniedException(); }
        $form = $this->createForm(PlayerFormType::class, $player, []);

        return $this->render('players/form.html.twig', [
            'player' => $player,
            'form' => $form->createView(),
            'password' => $password,
        ]);
    }

    /**
     * @param string $password
     * @return Owner
     */
    private function getOwner(string $password)
    {
        if ( !preg_match('/^[0-9a-f]{64}$/', $password) ) { throw new AccessDeniedException(); }
        $owner = $this->em->getRepository(Owner::class)->findOneBy(['password' => $password]);
        if ( empty($owner) || !$owner instanceof Owner) { throw new AccessDeniedException(); }
        return $owner;
    }
}