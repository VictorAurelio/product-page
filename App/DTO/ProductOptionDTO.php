<?php

namespace App\DTO;

class ProductOptionDTO implements DTOInterface
{
    private $_id;
    private $_productId;
    private $_optionId;
    private $_optionValue;
    public function getId() {
        return $this->_id;
    }
    public function setId($id) {
        $this->_id = $id;
    }
    public function getProductId() {
        return $this->_productId;
    }
    public function setProductId(ProductDTO $productDTO) {
        $this->_productId = $productDTO->getId();
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
        return [
            'productId' => $this->_productId,
            'optionId' => $this->_optionId,
            'optionValue' => $this->_optionValue,
        ];
    }
}