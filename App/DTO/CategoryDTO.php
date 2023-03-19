<?php

namespace App\DTO;

use App\DTO\DTOInterface;

/**
 * Summary of CategoryDTO
 */
class CategoryDTO implements DTOInterface
{
    private $_id;
    private $_name;
    private $_parent_id;
    public function getParentId() {
        return $this->_parent_id;
    }
    public function setParentId($parent_id) {
        $this->_parent_id = $parent_id;
    }
    public function getId()
    {
        return $this->_id;
    }
    public function setId($id)
    {
        $this->_id = $id;
    }
    public function getName()
    {
        return $this->_name;
    }
    public function setName($name)
    {
        $this->_name = $name;
    }
    public function toArray(): array
    {
        return [];
    }
}