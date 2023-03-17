<?php

namespace App\Models\Product;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Model;
use App\DTO\DTOInterface;

abstract class Product extends Model
{
    protected const TABLESCHEMA = 'products';
    protected const TABLESCHEMAID = 'id';

    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, $connection);
    }

    public function store(DTOInterface $productDTO)
    {
    }

    abstract public function specificAttributes(DTOInterface $productDTO): array;
}