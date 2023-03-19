<?php

namespace App\DTO;

use App\DTO\DTOInterface;

/**
 * Summary of OptionDTO
 */
class OptionDTO implements DTOInterface
{
    private $_id;
    private $_value;
    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }
    public function getValue()
    {
        return $this->_value;
    }    
    public function setValue($value)
    {
        $this->_value = $value;
    }  
    public function toArray(): array
    {
        return [];
    }
}