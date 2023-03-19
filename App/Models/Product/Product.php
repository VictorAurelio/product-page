<?php

namespace App\Models\Product;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DAO\DAO;
use App\Core\Database\DatabaseService\DatabaseService;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Core\Model;
use App\DTO\DTOInterface;

abstract class Product extends Model
{
    public const TABLESCHEMA = 'products';
    public const TABLESCHEMAID = 'id';

    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, $connection);
    }
    public function getDao(): DAO
    {
        return new DAO(
            new DatabaseService($this->connection),
            new MysqlQueryBuilder($this->connection),
            self::TABLESCHEMA,
            self::TABLESCHEMAID
        );
    }
    abstract public function specificAttributes(DTOInterface $productDTO): array;
}