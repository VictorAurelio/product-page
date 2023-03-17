<?php

namespace App\Models\Product;

use App\DTO\DTOInterface;

class Book extends Product
{
    public function specificAttributes(DTOInterface $productDTO): array
    {
        return [];
    }
}