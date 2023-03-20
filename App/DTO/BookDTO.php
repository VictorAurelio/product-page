<?php

namespace App\DTO;

class BookDTO extends ProductDTO
{
    private float $_weight;    
    public function getWeight(): float
    {
        return $this->_weight;
    }
    public function setWeight(float $weight): void
    {
        $this->_weight = $weight;
    }
    public function toArray(): array
    {
        $parentArray = parent::toArray();
        return array_merge($parentArray, [
            'weight' => $this->_weight,
        ]);
    }
}