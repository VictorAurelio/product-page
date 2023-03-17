<?php

namespace App\Core\Database\DAO;

use App\Core\Database\DAO\DAOInterface;
use InvalidArgumentException;
use App\DTO\DTOInterface;
use App\DTO\UserDTO;
use Throwable;

/**
 * Summary of UserDAO
 */
// App/Core/Database/DAO/UserDAO.php
class UserDAO implements DAOInterface
{
    protected DAO $dao;

    /**
     * Summary of __construct
     * 
     * @param DAO $dao
     */
    public function __construct(DAO $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Summary of create
     * 
     * @param DTOInterface $data
     * 
     * @throws InvalidArgumentException
     * 
     * @return bool
     */
    public function create(DTOInterface $data): bool
    {
        if (!$data instanceof UserDTO) {
            throw new InvalidArgumentException('Expected UserDTO instance.');
        }
        try {
            // Convert UserDTO to array
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
    }
    /**
     * Summary of read
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
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
    public function update(DTOInterface $data, string $primaryKey): bool
    {
        if (!$data instanceof UserDTO) {
            throw new InvalidArgumentException('Expected UserDTO instance.');
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
    public function delete(DTOInterface $conditions): bool
    {
        if (!$conditions instanceof UserDTO) {
            throw new InvalidArgumentException('Expected UserDTO instance.');
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
    public function findByEmail(UserDTO $fields): ?UserDTO
    {
        if (!$fields instanceof UserDTO) {
            throw new InvalidArgumentException('Expected UserDTO instance.');
        }
    
        $fieldsArray = $fields->toArray();
    
        $sqlQuery = $this->dao->getQueryBuilder()->buildQuery(
            [
                'type' => 'search',
                'selectors' => ['email' => $fieldsArray['email']],
                'table' => $this->dao->getSchema()
            ]
        )->exactSearchQuery();
    
        $this->dao->getDataMapper()->persist(
            $sqlQuery,
            $this->dao->getDataMapper()->buildQueryParameters([], ['email' => $fieldsArray['email']]),
            false
        );
        $result = $this->dao->getDataMapper()->result();
    
        if ($result === null) {
            return null;
        }
    
        $userDTO = new UserDTO();
        $userDTO->setId($result->id);
        $userDTO->setName($result->name);
        $userDTO->setEmail($result->email);
        $userDTO->setPassword($result->password);
    
        return $userDTO;
    }
    public function rawQuery(string $rawQuery, DTOInterface $conditions)
    {
    }
}
