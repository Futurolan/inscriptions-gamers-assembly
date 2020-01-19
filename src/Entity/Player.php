<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * Class Player
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 * @UniqueEntity("id")
 */
class Player
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=250, unique=false, nullable=true)
     * @Assert\NotBlank
     */
    private $firstname;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=250, unique=false, nullable=true)
     * @Assert\NotBlank
     */
    private $lastname;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=250, unique=false)
     * @Assert\NotBlank
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, unique=false, nullable=false)
     */
    private $owner;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=250, unique=false, nullable=true)
     */
    private $pseudo;

    /**
     * @var DateTime|null
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotBlank
     * @Assert\DateTime
     */
    private $birthdate;

    /**
     * Identifiant compte
     * @var string|null
     * @ORM\Column(type="string", length=250, unique=false, nullable=true)
     */
    private $identifiantCompte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="players")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament", inversedBy="teams")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $tournament;

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
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     */
    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     */
    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * @param string $pseudo|null
     */
    public function setPseudo(?string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param mixed $team
     */
    public function setTeam($team): void
    {
        $this->team = $team;
    }

    /**
     * @return Tournament|null
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
     * @return string
     */
    public function getOwner(): string
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     */
    public function setOwner(string $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return DateTime|null
     */
    public function getBirthdate(): ?DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param DateTime $birthdate
     * @return Player
     */
    public function setBirthdate(?DateTime $birthdate): Player
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdentifiantCompte(): ?string
    {
        return $this->identifiantCompte;
    }

    /**
     * @param string|null $identifiantCompte
     * @return Player
     */
    public function setIdentifiantCompte(?string $identifiantCompte): Player
    {
        $this->identifiantCompte = $identifiantCompte;
        return $this;
    }
}