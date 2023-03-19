<?php

namespace App\Core\Database\DAO\Product;

use App\Core\Database\DAO\DAO;
use App\Core\Database\DAO\DAOInterface;
use App\Core\Model;
use App\Core\Model\ProductCategory;
use App\Core\Model\ProductCategoryModel;
use App\DTO\DTOInterface;
use App\DTO\ProductCategoryDTO;
use InvalidArgumentException;
use Throwable;

class ProductCategoryDAO implements DAOInterface
{
    private DAO $_dao;

    public function __construct(ProductCategory $productCategoryDAO)
    {
        $this->_dao = $productCategoryDAO->getDao();
    }
    
    public function create(DTOInterface $data): bool
    {
        if (!$data instanceof ProductCategoryDTO) {
            throw new InvalidArgumentException('Expected ProductCategoryDTO instance.');
        }
        try {
            // Convert ProductCategoryDTO to array
            $fields = $data->toArray();

            $args = [
                'table' => $this->_dao->getSchema(),
                'type' => 'insert',
                'fields' => $fields
            ];
            $query = $this->_dao->getQueryBuilder()->buildQuery($args)->insertQuery();
            $this->_dao->getDataMapper()->persist(
                $query,
                $this->_dao->getDataMapper()->buildQueryParameters($fields)
            );

            if ($this->_dao->getDataMapper()->numRows() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
        return false;
    }
    public function read(
        array $selectors = [],
        array $conditions = [],
        array $parameters = [],
        array $optional = []
    ): array {
        // Implementar read() para ProductCategory
        return [];
    }

    public function update(DTOInterface $data, string $primaryKey): bool
    {
        // Implementar update() para ProductCategory
        return false;
    }

    public function delete(DTOInterface $conditions): bool
    {
        // Implementar delete() para ProductCategory
        return false;
    }

    public function rawQuery(string $rawQuery, DTOInterface $conditions)
    {
        // Implementar rawQuery() para ProductCategory
    }
}
