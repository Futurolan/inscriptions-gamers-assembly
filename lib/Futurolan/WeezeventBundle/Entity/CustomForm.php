<?php


namespace Futurolan\WeezeventBundle\Entity;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class CustomForm
 * @package Futurolan\WeezeventBundle\Entity
 */
class CustomForm
{
    /**
     * @var array|null
     * @Serializer\Type("array")
     */
    private $form;

    /**
     * @return array|null
     */
    public function getForm(): ?array
    {
        return $this->form;
    }

    /**
     * @param $value
     * @return int|string|null
     */
    public function getCustomAnwserId($value) {
        $res = null;
        foreach( $this->getForm() as $key => $item ) {
            if ($value == $item) { return $key; }
        }
        return $res;
    }
}