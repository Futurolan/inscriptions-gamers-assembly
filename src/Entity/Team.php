<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Team
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 * @UniqueEntity("id")
 */
class Team
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, unique=false)
     */
    private $team_name;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, unique=false)
     */
    private $owner_last_name;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, unique=false)
     */
    private $owner_first_name;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, unique=false)
     */
    private $owner_email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament", inversedBy="teams")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $tournament;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="team")
     */
    private $players;

    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTeamName(): string
    {
        return $this->team_name;
    }

    /**
     * @param string $team_name
     */
    public function setTeamName(string $team_name): void
    {
        $this->team_name = $team_name;
    }

    /**
     * @return string
     */
    public function getOwnerLastName(): string
    {
        return $this->owner_last_name;
    }

    /**
     * @param string $owner_last_name
     */
    public function setOwnerLastName(string $owner_last_name): void
    {
        $this->owner_last_name = $owner_last_name;
    }

    /**
     * @return string
     */
    public function getOwnerFirstName(): string
    {
        return $this->owner_first_name;
    }

    /**
     * @param string $owner_first_name
     */
    public function setOwnerFirstName(string $owner_first_name): void
    {
        $this->owner_first_name = $owner_first_name;
    }

    /**
     * @return string
     */
    public function getOwnerEmail(): string
    {
        return $this->owner_email;
    }

    /**
     * @param string $owner_email
     */
    public function setOwnerEmail(string $owner_email): void
    {
        $this->owner_email = $owner_email;
    }

    /**
     * @return mixed
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * @param mixed $tournament
     */
    public function setTournament($tournament): void
    {
        $this->tournament = $tournament;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }
}