<?php

/**
 * This file is part of my Product Page project.
 *
 * @category  DAO
 * @package   App\Core\Database\DAO
 * @author    Victor Aurélio Rodrigues Ribeiro <victoraurelio_198@hotmail.com>
 * @copyright (c) 2023 Victor Aurelio
 * @link      https://github.com/VictorAurelio/product-page
 */

namespace App\Database\DAO\Product;

use App\Core\Database\DAO\DAO;
use App\Core\Database\DAO\DAOInterface;
use App\DTO\DTOInterface;
use App\DTO\ProductDTO;
use App\Models\Product\Product;
use InvalidArgumentException;
use Throwable;

/**
 * Summary of ProductDAO
 */
class ProductDAO implements DAOInterface
{
    protected DAO $dao;
    public function __construct(Product $productModel)
    {
        $this->dao = $productModel->getDao();
    }

    /**
     * This method receives a DTO with the data to be created and
     * returns true if the creation was successful or false otherwise.
     * 
     * @param DTOInterface $data
     * @throws InvalidArgumentException
     * 
     * @return bool
     */
    public function create(DTOInterface $data): bool
    {
        if (!$data instanceof ProductDTO) {
            throw new InvalidArgumentException('Expected ProductDTO instance.');
        }
        try {
            // Convert ProductDTO to array
            $fields = $data->toArray();

            $args = [
                'table' => $this->dao->getSchema(),
                'type' => 'insert',
                'fields' => $fields
            ];
            $query = $this->dao->getQueryBuilder()->buildQuery($args)->insertQuery();
            $this->dao->getDataMapper()->persist(
                $query,
                $this->dao->getDataMapper()->buildQueryParameters($fields)
            );

            if ($this->dao->getDataMapper()->numRows() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
        return false;
    }
    
    /**
     * This method receives an array of selectors, an array of conditions,
     * an array of parameters, and an array of optional parameters.
     * It returns an array with the results of the search. If there's no data,
     * it returns an array with the message "no data".
     * 
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * 
     * @return array
     */
    public function read(
        array $selectors = [],
        array $conditions = [],
        array $parameters = [],
        array $optional = []
    ): array {
        try {
            $args = [
                'table' => $this->dao->getSchema(),
                'type' => 'select',
                'selectors' => $selectors,
                'conditions' => $conditions,
                'params' => $parameters,
                'extras' => $optional
            ];
            $query = $this->dao->getQueryBuilder()->buildQuery($args)->selectQuery();
            $this->dao->getDataMapper()->persist(
                $query,
                $this->dao->getDataMapper()->buildQueryParameters($conditions, $parameters)
            );
            if ($this->dao->getDataMapper()->numRows() > 0) {
                return $this->dao->getDataMapper()->results();
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
        return ['no data'];
    }
    /**
     * This method receives a DTO with the data to be updated and the primary key
     * of the record to be updated. It returns true if the update was successful
     * or false otherwise.
     * 
     * @param DTOInterface $data
     * @param string $primaryKey
     * 
     * @throws InvalidArgumentException
     * 
     * @return bool
     */
    public function update(DTOInterface $data, string $primaryKey): bool
    {
        if (!$data instanceof ProductDTO) {
            throw new InvalidArgumentException('Expected ProductDTO instance.');
        }

        $fields = $data->toArray();

        try {
            $args = [
                'table' => $this->dao->getSchema(),
                'type' => 'update',
                'fields' => $fields,
                'primary_key' => $primaryKey
            ];
            $query = $this->dao->getQueryBuilder()->buildQuery($args)->updateQuery();
            $this->dao->getDataMapper()->persist(
                $query,
                $this->dao->getDataMapper()->buildQueryParameters($fields)
            );
            if ($this->dao->getDataMapper()->numRows() === 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }

        return false;
    }
    /**
     * This method receives a DTO with the conditions to be used to delete a record.
     * It returns true if the deletion was successful or false otherwise.
     * 
     * @param DTOInterface $conditions
     * @throws InvalidArgumentException
     * 
     * @return bool
     */
    public function delete(DTOInterface $conditions): bool
    {
        if (!$conditions instanceof ProductDTO) {
            throw new InvalidArgumentException('Expected ProductDTO instance.');
        }

        $conditionArray = $conditions->toArray();

        try {
            $args = [
                'table' => $this->dao->getSchema(),
                'type' => 'delete',
                'conditions' => $conditionArray
            ];
            $query = $this->dao->getQueryBuilder()->buildQuery($args)->deleteQuery();
            $this->dao->getDataMapper()->persist(
                $query,
                $this->dao->getDataMapper()->buildQueryParameters($conditionArray)
            );
            if ($this->dao->getDataMapper()->numRows() === 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }

        return false;
    }
    /**
     * This method receives an personalized sql query as a string and a ProductDTO
     * with the conditions and return the results. If there's no results it'll
     * return an array with the message "no data".
     * Usage example:
     * $rawQuery = "SELECT * FROM products WHERE 
     *      category_id = :category_id AND price < :max_price;";
     * $results = $bookDAO->rawQuery($rawQuery, $productDTO)...
     * 
     * @param string $rawQuery
     * @param DTOInterface $conditions
     * 
     * @throws InvalidArgumentException
     * 
     * @return array
     */
    public function rawQuery(string $rawQuery, DTOInterface $conditions): array
    {
        if (!$conditions instanceof ProductDTO) {
            throw new InvalidArgumentException('Expected ProductDTO instance.');
        }

        $conditionArray = $conditions->toArray();

        try {
            $this->dao->getDataMapper()->persist(
                $rawQuery,
                $this->dao->getDataMapper()->buildQueryParameters($conditionArray)
            );
            
            if ($this->dao->getDataMapper()->numRows() > 0) {
                return $this->dao->getDataMapper()->results();
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }

        return ['no data'];
    }

}
