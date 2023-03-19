<?php

namespace App\Core\Model;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DAO\DAO;
use App\Core\Database\DAO\Product\ProductCategoryDAO;
use App\Core\Database\DatabaseService\DatabaseService;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Core\Model;
use App\DTO\ProductCategoryDTO;

class ProductCategory extends Model
{
    protected const TABLESCHEMA = 'product_categories';
    protected const TABLESCHEMAID = 'id';
    protected ProductCategoryDAO $productCategoryDAO;

    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, $connection);
        $this->productCategoryDAO = new ProductCategoryDAO($this);
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
    public function createProductCategory(ProductCategoryDTO $productCategoryDTO): bool
    {
        return $this->productCategoryDAO->create($productCategoryDTO);
    }
}
