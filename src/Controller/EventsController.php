<?php


namespace App\Controller;

use App\Service\ParameterService;
use App\Service\SynchroEventsService;
use Futurolan\WeezeventBundle\Client\WeezeventClient;
use Futurolan\WeezeventBundle\Entity\Participant;
use Futurolan\WeezeventBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Exception\GuzzleException;
use \Exception as Exception;

/**
 * Class listEventsController
 * @package App\Controller
 * @Security("is_granted('ROLE_USER')")
 */
class EventsController extends AbstractController
{
    /** @var WeezeventClient */
    private $weezeventClient;

    /** @var ParameterService */
    private $parameterService;

    /** @var SynchroEventsService */
    private $synchroEventsService;

    /**
     * listEventsController constructor.
     * @param WeezeventClient $weezeventClient
     * @param ParameterService $parameterService
     * @param SynchroEventsService $synchroEventsService
     */
    public function __construct(WeezeventClient $weezeventClient, ParameterService $parameterService, SynchroEventsService $synchroEventsService)
    {
        $this->weezeventClient = $weezeventClient;
        $this->parameterService = $parameterService;
        $this->synchroEventsService = $synchroEventsService;

        $this->weezeventClient->setApiToken($this->parameterService->get($this->parameterService::API_TOKEN));
    }

    /**
     * @Route("/admin/test", name="testPage")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function test()
    {
        dump($this->weezeventClient->getParticipantsByTicket(1852372)[0]);
        dd($this->weezeventClient->getParticipantsByTicket(1838355)[0]);
    }


    /**
     * @Route("/events", name="eventsListPage")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function eventsListAction()
    {
        return $this->render("events/events.html.twig", [
            'events' => $this->weezeventClient->getEvents(false),
        ]);
    }

    /**
     * @Route("/event/{eventID}/ticket/{ticketID}", name="eventParticipantsByTicketPage")
     * @param string $eventID
     * @param string $ticketID
     * @return Response
     * @throws GuzzleException
     * @Security("is_granted('ROLE_USER')")
     */
    public function eventParticipantsByTicketAction(string $eventID, string $ticketID)
    {
        $ticket = $this->weezeventClient->getTicket($eventID, $ticketID);
        $participants = $this->weezeventClient->getParticipantsByTicket($ticketID);
        if ( $ticket->getGroupSize() > 0 ) {
            $isTeam = true;
            $teams = $this->synchroEventsService->participantsToTeams($participants);
            $participants = null;
        } else {
            $isTeam = false;
            $teams = null;
        }

        return $this->render("events/eventParticipants.html.twig", [
            'ticket' => $ticket,
            'isTeam' => $isTeam,
            'teams' => $teams,
            'participants' => $participants,
        ]);
    }


    /**
     * @Route("/event/{eventID}", name="eventTicketsListPage")
     * @param string $eventID
     * @param ParameterService $parameterService
     * @return Response
     * @throws Exception
     */
    public function eventTicketsAction(string $eventID, ParameterService $parameterService)
    {
        return $this->render("events/eventTickets.html.twig", [
            'eventTickets' => $this->weezeventClient->getTicketsByEvent($eventID),
            'DefaultCategory' => $parameterService->get($parameterService::DEFAULT_CATEGORY_NAME),
        ]);
    }

}