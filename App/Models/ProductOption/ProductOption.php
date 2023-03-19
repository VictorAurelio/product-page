<?php

namespace App\Models\ProductOption;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DAO\DAO;
use App\Core\Database\DatabaseService\DatabaseService;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Core\Model;
use App\DAO\ProductOptionDAO;
use App\DTO\ProductOptionDTO;

class ProductOption extends Model
{
    protected const TABLESCHEMA = 'product_options';
    protected const TABLESCHEMAID = 'id';
    protected ProductOptionDAO $productOptionDAO;

    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, $connection);
        $this->productOptionDAO = new ProductOptionDAO($this);
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

    public function createOption(ProductOptionDTO $productOptionDAO): bool
    {
        return $this->productOptionDAO->create($productOptionDAO);
    }
}