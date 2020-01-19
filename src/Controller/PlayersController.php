<?php


namespace App\Controller;


use App\Entity\Owner;
use App\Entity\Player;
use App\Form\PlayerFormType;
use App\Service\ParameterService;
use Futurolan\WeezeventBundle\Client\WeezeventClient;
use Futurolan\WeezeventBundle\Entity\PlayerPatch;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Exception;

class PlayersController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var WeezeventClient */
    private $weezeventClient;

    /** @var ParameterService */
    private $parameterService;

    /**
     * PlayersController constructor.
     * @param EntityManagerInterface $em
     * @param WeezeventClient $weezeventClient
     * @param ParameterService $parameterService
     */
    public function __construct(EntityManagerInterface $em, WeezeventClient $weezeventClient, ParameterService $parameterService)
    {
        $this->em = $em;
        $this->weezeventClient = $weezeventClient;
        $this->parameterService = $parameterService;

        $this->weezeventClient->setApiToken($this->parameterService->get($this->parameterService::API_TOKEN));
    }

    /**
     * @Route("/player/{password}", methods={"GET"}, name="playerPage")
     * @param string $password
     * @return Response
     * @throws Exception
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
     * @Route("/player/{password}/modify/{player}", methods={"GET", "POST"}, name="playerFormPage")
     * @param string $password
     * @param Player $player
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function playerFormPage(string $password, Player $player, Request $request)
    {
        $owner = $this->getOwner($password);
        if ( $player->getOwner() !== $owner->getEmail() ) { throw new AccessDeniedException(); }

        $playerOriginal = clone $player;
        $form = $this->createForm(PlayerFormType::class, $player, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $player->setLastname(mb_strtoupper($player->getLastname()));
            $player->setFirstname(mb_convert_case($player->getFirstname(), MB_CASE_TITLE, "UTF-8"));
            $playerForm = $this->playerToPlayerPatch($playerOriginal, $player);
            if ( $playerForm->isModified() ) {
                $patch = $this->weezeventClient->editParticipant([$playerForm]);
                if ( is_array($patch) && key_exists('total_added', $patch) && $patch['total_added'] === 1 ) {
                    $this->em->persist($player);
                    $this->em->flush();
                    $this->addFlash('success', "Ticket successfully modified !");
                } else {
                    $this->addFlash('error', "error_ticket_modification_1");
                }
            }

            return $this->redirectToRoute('playerPage', ['password' => $password]);
        }

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

    /**
     * @param Player $playerOriginal
     * @param Player $playerEdited
     * @return PlayerPatch
     * @throws Exception
     */
    private function playerToPlayerPatch(Player $playerOriginal, Player $playerEdited)
    {
        $fields = [];
        foreach ($playerOriginal->getTournament()->getCustomFields() as $field) {
            $fields[$field['label']] = $field['id'];
        }

        $playerPatch = new PlayerPatch();
        $playerPatch
            ->setIdEvenement($playerOriginal->getTournament()->getCategory()->getEvent()->getId())
            ->setIdParticipant($playerOriginal->getId())
        ;

        if ( $playerOriginal->getEmail() !== $playerEdited->getEmail() ) { $playerPatch->setEmail($playerEdited->getEmail()); }
        if ( $playerOriginal->getLastname() !== $playerEdited->getLastname() ) { $playerPatch->setNom($playerEdited->getLastname()); }
        if ( $playerOriginal->getFirstname() !== $playerEdited->getFirstname() ) { $playerPatch->setPrenom($playerEdited->getFirstname()); }
        if ( $playerOriginal->getPseudo() !== $playerEdited->getPseudo() ) {
            $playerPatch->addForm($fields['Pseudo'], $playerEdited->getPseudo());
        }
        if ( $playerOriginal->getIdentifiantCompte() !== $playerEdited->getIdentifiantCompte() ) {
            $playerPatch->addForm($fields['Identifiant compte'], $playerEdited->getIdentifiantCompte());
        }
        if ( $playerOriginal->getBirthdate() !== $playerEdited->getBirthdate() ) {
            $playerPatch->addForm('date_de_naissance', $playerEdited->getBirthdate()->format('d/m/Y'));
        }


        return $playerPatch;
    }
}