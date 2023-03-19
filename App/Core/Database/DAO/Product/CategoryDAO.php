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

namespace App\Core\Database\DAO;

use App\Core\Database\DAO\DAOInterface;
use App\DTO\CategoryDTO;
use App\DTO\DTOInterface;
use App\Models\Category\Category;
use InvalidArgumentException;
use Throwable;

/**
 * Summary of CategoryDAO
 */
// App/Core/Database/DAO/CategoryDAO.php
class CategoryDAO implements DAOInterface
{
    protected DAO $dao;
    
    public function __construct(Category $categoryModel)
    {
        $this->dao = $categoryModel->getDao();
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
        if (!$data instanceof CategoryDTO) {
            throw new InvalidArgumentException('Expected CategoryDTO instance.');
        }
        try {
            // Convert CategoryDTO to array
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
    public function read(
        array $selectors = [],
        array $conditions = [],
        array $parameters = [],
        array $optional = []
    ): array {
        return  [];
    }
    public function update(DTOInterface $data, string $primaryKey): bool
    {
        return false;
    }
    public function delete(DTOInterface $conditions): bool
    {
        return false;
    }
    public function rawQuery(string $rawQuery, DTOInterface $conditions)
    {
    }
}
