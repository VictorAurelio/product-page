<?php

/**
 * This file is part of my Product Page project.
 *
 * @category  DAO
 * @package   App\Core\Database\DAO\Product
 * @author    Victor Aurélio Rodrigues Ribeiro <victoraurelio_198@hotmail.com>
 * @copyright (c) 2023 Victor Aurelio
 * @link      https://github.com/VictorAurelio/product-page
 */

namespace App\Core\Database\DAO\Product;

use App\Models\ProductOption\ProductOption;
use App\Core\Database\DAO\DAOInterface;
use App\Core\Database\DAO\DAO;
use App\DTO\Product\ProductOptionDTO;
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

    /**
     * Summary of create
     * @param DTOInterface $data
     * @throws \InvalidArgumentException
     * @return int|null
     */
    public function create(DTOInterface $data): ?int
    {
        if (!$data instanceof ProductOptionDTO) {
            throw new \InvalidArgumentException(
                'Expected ProductOptionDTO instance.'
            );
        }
        try {
            // Convert ProductOptionDTO to array
            $fields = $data->toArray();

            $args = [
                'table' => $this->_dao->getSchema(),
                'type' => 'insert',
                'fields' => $fields
            ];
            $query = $this->_dao
                ->getQueryBuilder()
                ->buildQuery($args)
                ->insertQuery();
                echo '<br><br>';
                var_dump($query);
                echo '<br><br>';
            $this->_dao
                ->getDataMapper()
                ->persist(
                    $query,
                    $this->_dao->getDataMapper()->buildQueryParameters($fields)
                );

            if ($this->_dao->getDataMapper()->numRows() == 1) {
                return $this->_dao->lastID();
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }

        return 0;
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

    /**
     * Summary of rawQuery
     * 
     * @param string $rawQuery
     * @param DTOInterface $conditions
     * 
     * @return mixed
     */
    public function rawQuery(string $rawQuery, DTOInterface $conditions): mixed
    {
        // Implementar rawQuery() para ProductCategory
    }
}

