<?php

namespace App\DTO;

use App\DTO\DTOInterface;

/**
 * Summary of ProductDTO
 */
abstract class ProductDTO implements DTOInterface
{
    private $_id;
    private $_sku;
    private $_name;
    private $_price;
    private $_categoryId;

    public function getId()
    {
        return $this->_id;
    }
    public function setId($id)
    {
        $this->_id = $id;
    }
    public function getSku()
    {
        return $this->_sku;
    }    
    public function setSku($sku)
    {
        $this->_sku = $sku;
    }    
    public function getName()
    {
        return $this->_name;
    }    
    public function setName($name)
    {
        $this->_name = $name;
    }    
    public function getPrice()
    {
        return $this->_price;
    }    
    public function setPrice($price)
    {
        $this->_price = $price;
    }    
    public function getCategoryId()
    {
        return $this->_categoryId;
    }    
    public function setCategoryId($categoryId)
    {
        $this->_categoryId = $categoryId;
    }
    public function toArray(): array
    {
        return [
            'id' => $this->_id,
            'sku' => $this->_sku,
            'name' => $this->_name,
            'price' => $this->_price,
            'categoryId' => $this->_categoryId,
        ];
    }
}