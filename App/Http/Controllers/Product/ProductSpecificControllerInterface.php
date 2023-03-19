<?php

namespace App\Controllers\Product;

interface ProductSpecificControllerInterface
{
    public function insertProduct(array $data): array;
}