<?php


namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Tournament
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\TournamentRepository")
 * @UniqueEntity("id")
 */
class Tournament
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
    private $name;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $group_size;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $participants;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $quotas;

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateStart;

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEnd;

    /**
     * @var array|null
     * @ORM\Column(type="json", nullable=true)
     */
    private $customFields;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="tournaments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="tournament")
     */
    private $teams;

    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->teams = new ArrayCollection();
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getGroupSize(): int
    {
        return $this->group_size;
    }

    /**
     * @param int $group_size
     */
    public function setGroupSize(int $group_size): void
    {
        $this->group_size = $group_size;
    }

    /**
     * @return int
     */
    public function getParticipants(): int
    {
        return $this->participants;
    }

    /**
     * @param int $participants
     */
    public function setParticipants(int $participants): void
    {
        $this->participants = $participants;
    }

    /**
     * @return int
     */
    public function getQuotas(): int
    {
        return $this->quotas;
    }

    /**
     * @param int $quotas
     */
    public function setQuotas(int $quotas): void
    {
        $this->quotas = $quotas;
    }

    /**
     * @return DateTime
     */
    public function getDateStart(): ?DateTime
    {
        return $this->dateStart;
    }

    /**
     * @param DateTime $dateStart
     */
    public function setDateStart(?DateTime $dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return DateTime
     */
    public function getDateEnd(): ?DateTime
    {
        return $this->dateEnd;
    }

    /**
     * @param DateTime $dateEnd
     */
    public function setDateEnd(?DateTime $dateEnd): void
    {
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    /**
     * @return array|null
     */
    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    /**
     * @param array|null $customFields
     */
    public function setCustomFields(?array $customFields): void
    {
        $this->customFields = $customFields;
    }
}