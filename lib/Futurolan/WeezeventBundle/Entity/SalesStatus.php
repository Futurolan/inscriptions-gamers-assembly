<?php


namespace Futurolan\WeezeventBundle\Entity;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class SalesStatus
 * @package Futurolan\WeezeventBundle\Entity
 */
class SalesStatus
{
    /**
     * @var int
     * @Serializer\Type("int")
     *
     * 1 : Vente en cours
     * 2 : Bientôt en vente
     * 3 : Ventes terminées
     * 4 : ?
     * 5 : Évènement non publié ou clôturé
     */
    private $id_status;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $libelle_status;

    /**
     * @return int
     */
    public function getIdStatus(): int
    {
        return $this->id_status;
    }

    /**
     * @param int $id_status
     */
    public function setIdStatus(int $id_status): void
    {
        $this->id_status = $id_status;
    }

    /**
     * @return string
     */
    public function getLibelleStatus(): string
    {
        return $this->libelle_status;
    }

    /**
     * @param string $libelle_status
     */
    public function setLibelleStatus(string $libelle_status): void
    {
        $this->libelle_status = $libelle_status;
    }
}