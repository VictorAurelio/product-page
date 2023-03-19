<?php

namespace App\DTO;

class ProductOptionDTO implements DTOInterface
{
    private $_id;
    private $_productId;
    private $_optionId;
    private $_optionValue;

    // Getters and Setters

    public function getId() {
        return $this->_id;
    }
    public function setId($id) {
        $this->_id = $id;
    }
    public function getProductId() {
        return $this->_productId;
    }
    public function setProductId($productId) {
        $this->_productId = $productId;
    }
    public function getOptionId() {
        return $this->_optionId;
    }
    public function setOptionId($optionId) {
        $this->_optionId = $optionId;
    }
    public function getOptionValue() {
        return $this->_optionValue;
    }
    public function setOptionValue($optionValue) {
        $this->_optionValue = $optionValue;
    }
    public function toArray(): array
    {
        return [];
    }
}