<?php

namespace App\Models\Product;

use App\DTO\DTOInterface;

class Furniture extends Product
{
    public function specificAttributes(DTOInterface $productDTO): array
    {
        return [];
    }
}