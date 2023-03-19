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

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DAO\DAOInterface;
use App\Core\Database\DatabaseService\DatabaseService;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\DTO\DTOInterface;
use App\DTO\OptionDTO;
use App\Models\Option\Option;
use Throwable;

/**
 * Summary of UserDAO
 */
// App/Core/Database/DAO/UserDAO.php
class OptionDAO implements DAOInterface
{
    protected DAO $dao;
    
    public function __construct(Option $optionModel)
    {
        $this->dao = $optionModel->getDao();
    }
    public function create(DTOInterface $data): bool
    {
        if (!$data instanceof OptionDTO) {
            throw new \InvalidArgumentException('Expected OptionDTO instance.');
        }

        try {
            // Convert OptionDTO to array
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