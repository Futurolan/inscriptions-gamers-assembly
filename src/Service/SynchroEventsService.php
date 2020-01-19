<?php


namespace App\Service;


use App\Entity\Category;
use App\Entity\Owner;
use App\Entity\Player;
use App\Entity\Team;
use App\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use Futurolan\WeezeventBundle\Client\WeezeventClient;
use Futurolan\WeezeventBundle\Entity\Buyer;
use Futurolan\WeezeventBundle\Entity\Event;
use \Exception as Exception;
use Futurolan\WeezeventBundle\Entity\Form;
use Futurolan\WeezeventBundle\Entity\Participant;
use Futurolan\WeezeventBundle\Entity\Team as WeezeventTeam;
use DateTime;

/**
 * Class SynchroEventsService
 * @package App\Service
 */
class SynchroEventsService
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var WeezeventClient */
    private $weezeventClient;

    /** @var ParameterService */
    private $parameterService;

    /** @var array */
    private $participants = [];

    /** @var array */
    private $teams = [];

    /** @var Buyer[] */
    private $buyers = [];

    /** @var array */
    private $customFields = [];

    /**
     * ParameterService constructor.
     * @param EntityManagerInterface $em
     * @param ParameterService $parameterService
     * @param WeezeventClient $weezeventClient
     */
    public function __construct(EntityManagerInterface $em, ParameterService $parameterService, WeezeventClient $weezeventClient)
    {
        $this->em = $em;
        $this->weezeventClient = $weezeventClient;
        $this->parameterService = $parameterService;
        $this->weezeventClient->setApiToken($this->parameterService->get($this->parameterService::API_TOKEN));
    }

    /**
     * @return Event[]
     * @throws Exception
     */
    public function synchro()
    {
        $weezeventEvents = $this->weezeventClient->getEvents(false);
        $eventsDB = $events = [];
        foreach ($this->em->getRepository(\App\Entity\Event::class)->findAll() as $eventDB) {
            $eventsDB[$eventDB->getId()] = $eventDB;
        }

        foreach($weezeventEvents as $weezeventEvent) {
            if ( $weezeventEvent->getSalesStatus()->getIdStatus() === 1 ) {
//            if ( $weezeventEvent->getSalesStatus()->getIdStatus() >= 1 ) {
                $events[] = $weezeventEvent;
                if ( key_exists($weezeventEvent->getId(), $eventsDB) ) { unset($eventsDB[$weezeventEvent->getId()]); }
            }
        }

        /** Suppression des événements périmés en DB */
        if ( count($eventsDB) > 0 ) {
            foreach ($eventsDB as $item) {
                $this->em->remove($item);
            }
            $this->em->flush();
        }

        return $events;
    }

    /**
     * @param Event $weezeventEvent
     * @return bool
     * @throws Exception
     */
    public function synchroEvent(Event $weezeventEvent)
    {
        $event = $this->em->getRepository(\App\Entity\Event::class)->find($weezeventEvent->getId());

        if ( !$event instanceof \App\Entity\Event) {
            $event = new \App\Entity\Event();
            $event->setId($weezeventEvent->getId());
        }
        $event->setName($weezeventEvent->getName());
        $event->setDateStart($weezeventEvent->getDate()->getStart());
        $event->setDateEnd($weezeventEvent->getDate()->getEnd());
        $event->setStatus($weezeventEvent->getSalesStatus()->getIdStatus());

        $this->em->persist($event);
        $this->em->flush();

        return $this->synchroCategories($weezeventEvent);
    }

    /**
     * @param Event $weezeventEvent
     * @return bool
     * @throws Exception
     */
    public function synchroCategories(Event $weezeventEvent)
    {
        $tickets = $this->weezeventClient->getTicketsByEvent($weezeventEvent->getId());
        /** @var \App\Entity\Event $event */
        $event = $this->em->getRepository(\App\Entity\Event::class)->find($weezeventEvent->getId());

        $categoriesDB = [];
        /** @var Category $categoryDB */
        foreach ($this->em->getRepository(Category::class)->getAllCategoryFromEvent($event) as $categoryDB) { $categoriesDB[$categoryDB->getId()]= $categoryDB; }

        $weezeventCategory = $tickets->getCategories();
        if ( is_array($weezeventCategory) ) {

            foreach ($tickets->getCategories() as $cat) {
                if ( preg_match('/^(badges|visiteurs)$/i', $cat->getName()) ) { continue; }
                if ( key_exists($cat->getId(), $categoriesDB) ) { unset($categoriesDB[$cat->getId()]); }
                $category = $this->em->getRepository(Category::class)->find($cat->getId());
                if ( !$category instanceof Category) {
                    $category = new Category();
                    $category->setId($cat->getId());
                }
                $category->setName($cat->getName());
                $category->setEvent($event);

                $this->em->persist($category);
                $this->em->flush();

                $this->synchroTournaments($category, $cat);
            }
        }

        /** Suppression des catégories périmées en DB */
        if ( count($categoriesDB) > 0 ) {
            foreach ($categoriesDB as $item) {
                $this->em->remove($item);
            }
            $this->em->flush();
        }

        return true;
    }

    /**
     * @param Category $category
     * @param \Futurolan\WeezeventBundle\Entity\Category $weezeventCategory
     * @return bool
     * @throws Exception
     */
    public function synchroTournaments(Category $category, \Futurolan\WeezeventBundle\Entity\Category $weezeventCategory)
    {
        $tournamentsDB = [];

        /** @var Tournament $tournamentDB */
        foreach ($this->em->getRepository(Tournament::class)->getAllTournamentFromCategory($category) as $tournamentDB) { $tournamentsDB[$tournamentDB->getId()]= $tournamentDB; }

        $weezeventTournaments = $weezeventCategory->getTickets();
        if ( is_array($weezeventTournaments) ) {
            foreach ($weezeventTournaments as $ticket) {
                if ( key_exists($ticket->getId(), $tournamentsDB) ) { unset($tournamentsDB[$ticket->getId()]); }
                $tournament = $this->em->getRepository(Tournament::class)->find($ticket->getId());
                if ( !$tournament instanceof Tournament) {
                    $tournament = new Tournament();
                    $tournament->setId($ticket->getId());
                }

                $tournament->setName($ticket->getName());
                $tournament->setCategory($category);
                $tournament->setGroupSize(( $ticket->getGroupSize() > 0 ? $ticket->getGroupSize() : 1 ));
                $tournament->setQuotas($ticket->getQuotas());
                $tournament->setParticipants($ticket->getParticipants());
                $tournament->setDateStart($ticket->getStartSale());
                $tournament->setDateEnd($ticket->getEndSale());

                $this->em->persist($tournament);
                $this->em->flush();

                $this->synchroCustomFields($tournament);
                if ( $tournament->getGroupSize() > 1 ) { $this->synchroTeams($tournament); }
                $this->synchroPlayers($tournament);

            }
        }

        /** Suppression des tournois périmés en DB */
        if ( count($tournamentsDB) > 0 ) {
            foreach ($tournamentsDB as $item) {
                $this->em->remove($item);
            }
            $this->em->flush();
        }

        return true;
    }

    /**
     * @param Tournament $tournament
     * @throws Exception
     */
    public function synchroCustomFields(Tournament $tournament)
    {
        if ( !key_exists($tournament->getCategory()->getEvent()->getId(), $this->customFields) ) {
            $forms = $this->weezeventClient->getEventForm($tournament->getCategory()->getEvent()->getId());
            $this->customFields[$tournament->getCategory()->getEvent()->getId()] = [];

            foreach( $forms as $form ) {
                foreach ($form->getTickets() as $tickeId) {
                    if ( !key_exists($tickeId, $this->customFields[$form->getIdEvenement()]) ) {
                        $this->customFields[$form->getIdEvenement()][$tickeId] = $form;
                    }
                }
            }
        }

        $customFields = [];
        /** @var Form $form */
        $form = $this->customFields[$tournament->getCategory()->getEvent()->getId()][$tournament->getId()];
        foreach($form->getQuestionsParticipant() as $question) {
            if ( $question->getTypeId() === 0 ) {
                $customFields[] = [
                  'label' => $question->getLabel(),
                  'id' => $question->getId(),
                ];
            }
        }
        $tournament->setCustomFields($customFields);
        $this->em->persist($tournament);
        $this->em->flush();
    }

    /**
     * @param Tournament $tournament
     * @throws Exception
     */
    public function synchroTeams(Tournament $tournament)
    {
        $participants = $this->getParticipantsByTournament($tournament);
        $teams = $this->participantsToTeams($participants);
        $dbTeams = $this->getDbTeams($tournament);

        foreach ($teams as $team) {
            $dbTeam = $this->em->getRepository(Team::class)->find($team->getId());
            if ( !$dbTeam instanceof Team) {
                $dbTeam = new Team();
                $dbTeam->setId($team->getId());
            } else {
                unset($dbTeams[$team->getId()]);
            }

            $dbTeam->setTeamName($team->getName());
            $dbTeam->setOwnerEmail($team->getEmail());
            $dbTeam->setOwnerFirstName($team->getOwnerFirstName());
            $dbTeam->setOwnerLastName($team->getOwnerLastName());
            $dbTeam->setTournament($tournament);

            $this->em->persist($dbTeam);
            $this->teams[$dbTeam->getId()] = $dbTeam;
        }

        /** Suppression des équipes supprimées daans Weezevent */
        if ( count($dbTeams) > 0 ) {
            foreach ($dbTeams as $item) {
                $this->em->remove($item);
            }
        }

        $this->em->flush();
    }

    /**
     * @param Tournament $tournament
     * @throws Exception
     */
    public function synchroPlayers(Tournament $tournament)
    {
        $participants = $this->getParticipantsByTournament($tournament);
        $players = $this->getDbPlayers($tournament);


        foreach ($participants as $participant) {
            $emailBuyer = $this->getBuyerEmail($participant->getBuyer());
            if ( !empty($emailBuyer) && !$participant->isDeleted() ) {
                $player = $this->em->getRepository(Player::class)->find($participant->getIdParticipant());
                if ( !$player instanceof Player) {
                    $player = new Player();
                    $player->setId($participant->getIdParticipant());
                } else {
                    unset($players[$participant->getIdParticipant()]);
                }

                $player->setTournament($tournament);
                $player->setEmail($participant->getOwner()->getEmail());
                $player->setFirstname($participant->getOwner()->getFirstName());
                $player->setLastname($participant->getOwner()->getLastName());
                $player->setPseudo($this->getPlayerPseudo($participant));
                $player->setIdentifiantCompte($this->getPlayerIdentifiantCompte($participant));
                $player->setOwner($emailBuyer);
                $player->setBirthdate($this->getPlayerBirthdate($participant));

                if ( !empty($participant->getBuyer()->getIdAcheteur()) && key_exists($participant->getBuyer()->getIdAcheteur(), $this->teams) ) {
                    $player->setTeam($this->teams[$participant->getBuyer()->getIdAcheteur()]);
                }

                $this->em->persist($player);

                /** Construction de la table des propriétaires */
                $this->buyers[$emailBuyer] = $participant->getBuyer();
            }
        }

        /** Suppression des tournois périmés en DB */
        if ( count($players) > 0 ) {
            foreach ($players as $item) {
                $this->em->remove($item);
            }
        }

        $this->em->flush();
    }

    /**
     * @throws Exception
     */
    public function synchroBuyers()
    {
        $dbOwners = $this->em->getRepository(Owner::class)->findAll();
        $dbBuyers = [];
        foreach ($dbOwners as $dbOwner) {$dbBuyers[$dbOwner->getEmail()] = $dbOwner;}

        foreach ($this->buyers as $buyer) {
            $emailBuyer = $this->getBuyerEmail($buyer);
            if ( !key_exists($emailBuyer, $dbBuyers) || !$dbBuyers[$emailBuyer] instanceof Owner ) {
                $owner = new Owner();
                $owner->setEmail($emailBuyer);
            } else {
                $owner = $dbBuyers[$emailBuyer];
                unset($dbBuyers[$emailBuyer]);
            }

            $owner->setFirstname($buyer->getAcheteurFirstName());
            $owner->setLastname($buyer->getAcheteurLastName());
            $owner->createPassword();
            $this->em->persist($owner);
        }

        /** Suppression des propriétaires périmés en DB */
        if ( count($dbBuyers) > 0 ) {
            foreach ($dbBuyers as $item) {
                $this->em->remove($item);
            }
        }

        $this->em->flush();
    }

    /**
     * @param Tournament $tournament
     * @return Player[]
     */
    private function getDbPlayers(Tournament $tournament)
    {
        $dbPlayers = $this->em->getRepository(Player::class)->getAllPlayersFromTournament($tournament);
        $players = [];
        /** @var Player $dbPlayer */
        foreach ($dbPlayers as $dbPlayer) { $players[$dbPlayer->getId()] = $dbPlayer; }
        return $players;
    }

    /**
     * @param Tournament $tournament
     * @return Team[]
     */
    private function getDbTeams(Tournament $tournament)
    {
        $dbTeams = $this->em->getRepository(Team::class)->getAllTeamFromTournament($tournament);
        $teams = [];
        /** @var Team $dbTeam */
        foreach ($dbTeams as $dbTeam) { $teams[$dbTeam->getId()] = $dbTeam; }
        return $teams;
    }

    /**
     * @param Participant[] $participants
     * @return WeezeventTeam[]
     */
    public function participantsToTeams(array $participants)
    {
        /** @var WeezeventTeam[] $teams */
        $teams = [];

        /** @var Participant $participant */
        foreach($participants as $participant) {
            if ( !is_null($participant->getBuyer()->getIdAcheteur())  ) {
                if ( !key_exists($participant->getBuyer()->getIdAcheteur(), $teams) ) {
                    $team = new WeezeventTeam();
                    $team->setId($participant->getBuyer()->getIdAcheteur());
                    $team->setName($this->getTeamName($participant));
                    $team->setEmail($this->getBuyerEmail($participant->getBuyer()));
                    $team->setOwnerFirstName($participant->getBuyer()->getAcheteurFirstName());
                    $team->setOwnerLastName($participant->getBuyer()->getAcheteurLastName());
                    $teams[$participant->getBuyer()->getIdAcheteur()] = $team;
                }
                $teams[$participant->getBuyer()->getIdAcheteur()]->addMember($participant);
            }
        }
        return $teams;
    }

    /**
     * @param Tournament $tournament
     * @return Participant[]
     * @throws Exception
     */
    private function getParticipantsByTournament(Tournament $tournament)
    {
        if ( !key_exists($tournament->getId(), $this->participants) ) {
            $this->participants[$tournament->getId()] = $this->weezeventClient->getParticipantsByTicket($tournament->getId());
        }
        /** @var Participant $participant */
        foreach($this->participants[$tournament->getId()] as $key => $participant ) {
            if ( $participant->isDeleted() ) { unset($this->participants[$tournament->getId()][$key]); }
        }

        return $this->participants[$tournament->getId()];
    }

    /**
     * @param Participant $participant
     * @return string
     */
    private function getTeamName(Participant $participant)
    {
        foreach ($participant->getBuyer()->getAnswers() as $anwser) {
            if ( in_array($anwser->getLabel(), ["Dénomination de l'équipe", "Team Name"]) ) { return $anwser->getValue(); }
        }
        return (string)$participant->getBuyer()->getIdAcheteur();
    }

    /**
     * @param Participant $participant
     * @return string
     */
    private function getPlayerPseudo(Participant $participant)
    {
        foreach ($participant->getAnswers() as $anwser) {
            if ( $anwser->getLabel() === "Pseudo" ) { return $anwser->getValue(); }
        }
        return "";
    }

    /**
     * @param Participant $participant
     * @return string
     */
    private function getPlayerIdentifiantCompte(Participant $participant)
    {
        foreach ($participant->getAnswers() as $anwser) {
            if ( $anwser->getLabel() === "Identifiant compte" ) { return $anwser->getValue(); }
        }
        return "";
    }

    /**
     * @param Participant $participant
     * @return DateTime|null
     */
    private function getPlayerBirthdate(Participant $participant)
    {
        foreach ($participant->getAnswers() as $anwser) {
            if ( in_array($anwser->getLabel(), ["Date_de_naissance"]) && preg_match('/^([\d]{2})\/([\d]{2})\/([\d]{4})$/', $anwser->getValue()) ) {
                return DateTime::createFromFormat('d/m/Y', $anwser->getValue());
            }
        }
        return null;
    }

    /**
     * @param Buyer $buyer
     * @return string|null
     */
    private function getBuyerEmail(Buyer $buyer)
    {
        foreach ($buyer->getAnswers() as $anwser) {
            if ( $anwser->getLabel() === "Email" ) { return $anwser->getValue(); }
        }
        return null;
    }
}