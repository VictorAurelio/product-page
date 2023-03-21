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

use InvalidArgumentException;
use App\DTO\Product\DvdDTO;
use App\Models\Product\Dvd;
use App\DTO\DTOInterface;
use Throwable;

/**
 * Summary of DvdDAO
 */
class DvdDAO extends ProductDAO
{
    /**
     * Summary of dvdModel
     *
     * @var Dvd
     */
    protected Dvd $dvdModel;
    /**
     * Summary of __construct
     *
     * @param Dvd $dvdModel
     */
    public function __construct(Dvd $dvdModel)
    {
        parent::__construct($dvdModel);
        $this->dvdModel = $dvdModel;
    }
    public function lastId(): int
    {
        return parent::lastId();
    }
    /**
     * This method receives a DvdDTO with the data necessary to create a new dvd
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
        if (!$data instanceof DvdDTO) {
            throw new InvalidArgumentException('Expected DvdDTO instance.');
        }
        try {
            // Convert DvdDTO to array
            $fields = $data->toArray();
            // Remove the 'size' field
            unset($fields['size']);
            
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
     * This method receives a DvdDTO with the data necessary to update a dvd
     * and an ID representing the dvd to be updated. It updates the dvd record
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
        if (!$data instanceof DvdDTO) {
            throw new InvalidArgumentException('Expected DvdDTO instance.');
        }
    
        // Convert DvdDTO to array
        $fields = $data->toArray();
    
        // Merge specific attributes of Dvd with the Product attributes
        $mergedFields = array_merge(
            $fields, 
            $this->dvdModel->specificAttributes($data)
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
