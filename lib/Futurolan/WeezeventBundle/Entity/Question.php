<?php


namespace Futurolan\WeezeventBundle\Entity;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Question
 * @package Futurolan\WeezeventBundle\Entity
 */
class Question
{
    /**
     * @var int
     * @Serializer\Type("int")
     */
    private $id;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $type;

    /**
     * @var int
     * @Serializer\Type("int")
     */
    private $type_id;

    /**
     * @var bool
     * @Serializer\Type("bool")
     */
    private $required;

    /**
     * @var bool
     * @Serializer\Type("bool")
     */
    private $bo_only;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    private $label;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    private $field_type;

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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getTypeId(): int
    {
        return $this->type_id;
    }

    /**
     * @param int $type_id
     */
    public function setTypeId(int $type_id): void
    {
        $this->type_id = $type_id;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    /**
     * @return bool
     */
    public function isBoOnly(): bool
    {
        return $this->bo_only;
    }

    /**
     * @param bool $bo_only
     */
    public function setBoOnly(bool $bo_only): void
    {
        $this->bo_only = $bo_only;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string|null
     */
    public function getFieldType(): ?string
    {
        return $this->field_type;
    }

    /**
     * @param string|null $field_type
     */
    public function setFieldType(?string $field_type): void
    {
        $this->field_type = $field_type;
    }
}