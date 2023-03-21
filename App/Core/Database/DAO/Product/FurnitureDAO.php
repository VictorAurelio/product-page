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

use App\DTO\Product\FurnitureDTO;
use App\Models\Product\Furniture;
use InvalidArgumentException;
use App\DTO\DTOInterface;
use Throwable;

/**
 * Summary of FurnitureDAO
 */
class FurnitureDAO extends ProductDAO
{
    /**
     * Summary of furnitureModel
     *
     * @var Furniture
     */
    protected Furniture $furnitureModel;
    /**
     * Summary of __construct
     *
     * @param Furniture $furnitureModel
     */
    public function __construct(Furniture $furnitureModel)
    {
        parent::__construct($furnitureModel);
        $this->furnitureModel = $furnitureModel;
    }
    public function lastId(): int
    {
        return parent::lastId();
    }
    /**
     * This method receives a FurnitureDTO with the data necessary to create a new furniture
     * and creates a new record in the database. It returns true if the creation
     * was successful and false otherwise.
     *
     * @param DTOInterface $data
     *
     * @throws InvalidArgumentException
     *
     * @return ?int
     */
    public function create(DTOInterface $data): ?int
    {
        if (!$data instanceof FurnitureDTO) {
            throw new InvalidArgumentException('Expected FurnitureDTO instance.');
        }
        try {
            // Convert FurnitureDTO to array
            $fields = $data->toArray();
            // Remove the 'dimensions' field
            unset($fields['dimensions']);
            
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
                // Get the last inserted ID and return it
                return $this->dao->lastID();
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
        return 0; // Return 0 if the insert fails
    }
    /**
     * This method receives a FurnitureDTO with the data necessary to update a furniture
     * and an ID representing the furniture to be updated. It updates the furniture record
     * in the database and returns true if the update was successful and false
     * otherwise.
     *
     * @param DTOInterface $data
     * @param string       $primaryKey
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function update(DTOInterface $data, string $primaryKey): bool
    {
        if (!$data instanceof FurnitureDTO) {
            throw new InvalidArgumentException('Expected FurnitureDTO instance.');
        }
    
        // Convert FurnitureDTO to array
        $fields = $data->toArray();
    
        // Merge specific attributes of Furniture with the Product attributes
        $mergedFields = array_merge(
            $fields, 
            $this->furnitureModel->specificAttributes($data)
        );
        try {
            $args = [
                'table' => $this->dao->getSchema(),
                'type' => 'update',
                'fields' => $mergedFields,
                'primary_key' => $primaryKey
            ];
            $query = $this->dao->getQueryBuilder()->buildQuery($args)->updateQuery();
            $this->dao->getDataMapper()->persist(
                $query,
                $this->dao->getDataMapper()->buildQueryParameters($mergedFields)
            );
            if ($this->dao->getDataMapper()->numRows() === 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    
        return false;
    }
    public function getAllProducts()
    {
        return $this->read();
    }
    
}
