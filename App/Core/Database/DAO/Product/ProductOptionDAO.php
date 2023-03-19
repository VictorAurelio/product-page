<?php

namespace App\DAO;

use App\Models\ProductOption\ProductOption;
use App\Core\Database\DAO\DAOInterface;
use App\Core\Database\DAO\DAO;
use App\DTO\ProductOptionDTO;
use App\DTO\DTOInterface;
use Throwable;

/**
 * Summary of ProductOptionDAO
 */
class ProductOptionDAO implements DAOInterface
{
    private DAO $_dao;

    public function __construct(ProductOption $productOptionModel)
    {
        $this->_dao = $productOptionModel->getDao();
    }

    public function create(DTOInterface $data): bool
    {
        if (!$data instanceof ProductOptionDTO) {
            throw new \InvalidArgumentException('Expected ProductOptionDTO instance.');
        }

        try {
            // Convert ProductOptionDTO to array
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

