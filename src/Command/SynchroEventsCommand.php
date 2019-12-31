<?php


namespace App\Command;


use App\Service\ParameterService;
use App\Service\SynchroEventsService;
use Futurolan\WeezeventBundle\Client\WeezeventClient;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SynchroCommand
 * @package App\Command
 */
class SynchroEventsCommand extends Command
{
    protected static $defaultName = 'synchro:events';

    /** @var SymfonyStyle */
    private $io;

    /** @var WeezeventClient */
    private $weezeventClient;

    /** @var ParameterService */
    private $parameterService;

    /** @var SynchroEventsService */
    private $synchroEventsService;

    /**
     * SynchroEventsCommand constructor.
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
        parent::__construct();
    }


    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Synchronise les événements depuis Weezevent.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to synchronize all events from Weezevent...')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title("Synchronisation des événements");

        try {
            $events = $this->weezeventClient->getEvents(false);
        } catch (GuzzleException $e) {
            $this->io->getErrorStyle()->warning("Erreur: impossible de récupérer les événements chez Weezevent.");
            return 0;
        }

        $this->io->text(count($events) . " événements récupérés.");

        $this->io->progressStart(count($events));
        foreach ($events as $event) {
            $this->synchroEventsService->synchroEvent($event);
            $this->io->progressAdvance();
        }
        $this->io->progressFinish();

        return 1;
    }
}