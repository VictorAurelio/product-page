<?php

namespace App\Models\Product;

use App\DTO\DTOInterface;

class Dvd extends Product
{
    public function specificAttributes(DTOInterface $productDTO): array
    {
        return [];
    }
}