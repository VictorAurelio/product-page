<?php

namespace App\DTO;

use App\DTO\DTOInterface;
use InvalidArgumentException;

/**
 * Summary of OptionDTO
 */
class OptionDTO implements DTOInterface
{
    private ?int $_id;
    private string $_value;
    public function __construct(?int $id = null, string $value)
    {
        $this->_id = $id;
        $this->_value = $value;
    }
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
        if(empty(trim($value))) {
            throw new InvalidArgumentException('Option name cannot be empty.');
        }
        $this->_value = $value;
    }  
    public function toArray(): array
    {
        return [
            'id' => $this->_id,
            'value' => $this->_value
        ];
    }
}