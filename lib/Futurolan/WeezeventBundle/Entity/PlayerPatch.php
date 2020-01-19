<?php


namespace Futurolan\WeezeventBundle\Entity;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class PlayerPatch
 * @package Futurolan\WeezeventBundle\Entity
 */
class PlayerPatch
{
    /**
     * @var int
     * @Serializer\Type("int")
     */
    private $id_evenement;

    /**
     * @var int
     * @Serializer\Type("int")
     */
    private $id_participant;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    private $email;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    private $nom;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    private $prenom;

    /**
     * @var bool
     * @Serializer\Exclude()
     */
    private $modified = false;

    /**
     * @var array
     * @Serializer\Type("array")
     */
    private $form;

    /**
     * @return int
     */
    public function getIdEvenement(): int
    {
        return $this->id_evenement;
    }

    /**
     * @param int $id_evenement
     * @return PlayerPatch
     */
    public function setIdEvenement(int $id_evenement): PlayerPatch
    {
        $this->id_evenement = $id_evenement;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdParticipant(): int
    {
        return $this->id_participant;
    }

    /**
     * @param int $id_participant
     * @return PlayerPatch
     */
    public function setIdParticipant(int $id_participant): PlayerPatch
    {
        $this->id_participant = $id_participant;
        return $this;
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
     * @return PlayerPatch
     */
    public function setEmail(?string $email): PlayerPatch
    {
        if ( $this->email !== $email ) { $this->setModified(true); }
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     * @return PlayerPatch
     */
    public function setNom(?string $nom): PlayerPatch
    {
        if ( $this->nom !== $nom ) { $this->setModified(true); }
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string|null $prenom
     * @return PlayerPatch
     */
    public function setPrenom(?string $prenom): PlayerPatch
    {
        if ( $this->prenom !== $prenom ) { $this->setModified(true); }
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return bool
     */
    public function isModified(): bool
    {
        return $this->modified;
    }

    /**
     * @param bool $modified
     * @return PlayerPatch
     */
    public function setModified(bool $modified): PlayerPatch
    {
        $this->modified = $modified;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getForm(): ?array
    {
        return $this->form;
    }

    /**
     * @param array|null $form
     * @return PlayerPatch
     */
    public function setForm(?array $form): PlayerPatch
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return PlayerPatch
     */
    public function addForm($key, $value): PlayerPatch
    {
        if ( !is_array($this->form) ) { $this->form = []; }
        $this->setModified(true);
        $this->form[$key] = $value;
        return $this;
    }
}