<?php

namespace App\DTO;

class ProductCategoryDTO implements DTOInterface
{
    private int $_productId;
    private int $_categoryId;

    public function __construct(int $productId, int $categoryId)
    {
        $this->_productId = $productId;
        $this->_categoryId = $categoryId;
    }

    public function getProductId(): int
    {
        return $this->_productId;
    }

    public function getCategoryId(): int
    {
        return $this->_categoryId;
    }

    public function toArray(): array
    {
        return [];
    }
}

