<?php


namespace Futurolan\WeezeventBundle\Entity;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Form
 * @package Futurolan\WeezeventBundle\Entity
 */
class Form
{
    /**
     * @var int
     * @Serializer\Type("int")
     */
    private $id_form;

    /**
     * @var int
     * @Serializer\Type("int")
     */
    private $id_evenement;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $titre_page;

    /**
     * @var Question[]
     * @Serializer\Type("array<Futurolan\WeezeventBundle\Entity\Question>")
     */
    private $questions_buyer = [];

    /**
     * @var Question[]
     * @Serializer\Type("array<Futurolan\WeezeventBundle\Entity\Question>")
     */
    private $questions_participant = [];

    /**
     * @var array
     * @Serializer\Type("array")
     */
    private $tickets = [];

    /**
     * @return int
     */
    public function getIdForm(): int
    {
        return $this->id_form;
    }

    /**
     * @param int $id_form
     */
    public function setIdForm(int $id_form): void
    {
        $this->id_form = $id_form;
    }

    /**
     * @return int
     */
    public function getIdEvenement(): int
    {
        return $this->id_evenement;
    }

    /**
     * @param int $id_evenement
     */
    public function setIdEvenement(int $id_evenement): void
    {
        $this->id_evenement = $id_evenement;
    }

    /**
     * @return string
     */
    public function getTitrePage(): string
    {
        return $this->titre_page;
    }

    /**
     * @param string $titre_page
     */
    public function setTitrePage(string $titre_page): void
    {
        $this->titre_page = $titre_page;
    }

    /**
     * @return Question[]
     */
    public function getQuestionsBuyer(): array
    {
        return $this->questions_buyer;
    }

    /**
     * @param Question[] $questions_buyer
     */
    public function setQuestionsBuyer(array $questions_buyer): void
    {
        $this->questions_buyer = $questions_buyer;
    }

    /**
     * @return Question[]
     */
    public function getQuestionsParticipant(): array
    {
        return $this->questions_participant;
    }

    /**
     * @param Question[] $questions_participant
     */
    public function setQuestionsParticipant(array $questions_participant): void
    {
        $this->questions_participant = $questions_participant;
    }

    /**
     * @return array
     */
    public function getTickets(): array
    {
        return $this->tickets;
    }

    /**
     * @param array $tickets
     */
    public function setTickets(array $tickets): void
    {
        $this->tickets = $tickets;
    }
}