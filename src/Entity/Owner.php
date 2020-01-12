<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Exception;

/**
 * Class Owner
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\OwnerRepository")
 * @UniqueEntity("id")
 */
class Owner
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=250, unique=false, nullable=true)
     */
    private $firstname;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=250, unique=false, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, unique=true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, unique=true)
     */
    private $password;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Owner
     */
    public function setId(int $id): Owner
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return Owner
     */
    public function setFirstname(string $firstname): Owner
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return Owner
     */
    public function setLastname(string $lastname): Owner
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Owner
     */
    public function setEmail(string $email): Owner
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Owner
     */
    public function setPassword(string $password): Owner
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return Owner
     * @throws Exception
     */
    public function createPassword(): Owner
    {
        if ( empty($this->getPassword()) ) {
            $this->setPassword(bin2hex(random_bytes(32)));
        }
        return $this;
    }
}