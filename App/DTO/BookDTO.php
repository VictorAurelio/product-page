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
}