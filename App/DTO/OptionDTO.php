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
    private string $_name;
    public function __construct(?int $id = null, string $name)
    {
        $this->_id = $id;
        $this->_name = $name;
    }
    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }
    public function getName()
    {
        return $this->_name;
    }    
    public function setName($name)
    {
        if(empty(trim($name))) {
            throw new InvalidArgumentException('Option name cannot be empty.');
        }
        $this->_name = $name;
    }  
    public function toArray(): array
    {
        return [
            'id' => $this->_id,
            'name' => $this->_name
        ];
    }
}