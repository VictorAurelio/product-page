<?php

/**
 * This file is part of my Product Page project.
 *
 * @category  Database
 * @package   App\Core\Database
 * @author    Victor Aurélio Rodrigues Ribeiro <victoraurelio_198@hotmail.com>
 * @copyright (c) 2023 Victor Aurelio
 * @link      https://github.com/VictorAurelio/product-page
 */

namespace App\Core\Database\DatabaseService;

use App\Core\Database\DatabaseService\Exception\DatabaseServiceException;
use App\Core\Database\Connection\Connection;
use PDOStatement;
use PDOException;
use Throwable;
use PDO;

/**
 * Summary of DatabaseService
 */
class DatabaseService implements DatabaseServiceInterface
{
    protected Connection $connection;
    private PDOStatement $_statement;
    /**
     * Summary of __construct
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    /**
     * Summary of isEmpty
     * @param string|null $errorMessage
     * @throws DatabaseServiceException
     * @param mixed $value
     * @return void
     */
    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            throw new DatabaseServiceException($errorMessage);
        }
    }
    /**
     * Summary of isArray
     * @throws DatabaseServiceException
     * @param array $value
     * @return void
     */
    private function isArray(array $value)
    {
        if (!is_array($value)) {
            throw new DatabaseServiceException('Your argument needs to be an array');
        }
    }
    /**
     * Summary of prepare
     * @param string $sqlQuery
     * @return DatabaseService
     */
    public function prepare(string $sqlQuery): self
    {
        $this->_statement = $this->connection->pdo()->prepare($sqlQuery);
        return $this;
    }
    /**
     * Summary of bind
     * @param mixed $value
     * @return mixed
     */
    public function bind($value): mixed
    {
        try {
            switch ($value) {
                case is_bool($value):
                case intval($value);
                    $dataType = PDO::PARAM_INT;
                    break;
                case is_null($value):
                    $dataType = PDO::PARAM_NULL;
                    break;
                default:
                    $dataType = PDO::PARAM_STR;
                    break;
            }
            return $dataType;
        } catch (DatabaseServiceException $exception) {
            throw $exception;
        }
    }
   /**
     * @inheritDoc
     *
     * @param array $fields
     * @param boolean $isSearch
     * @return self
     */
    public function bindParameters(array $fields, bool $isSearch = false): self
    {
        $this->isArray($fields);
        if (is_array($fields)) {
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
    }
    /**
     * 
     * @param array $fields
     * @return PDOStatement
     * @throws BaseInvalidArgumentException
     */
    protected function bindValues(array $fields): PDOStatement
    {
        $this->isArray($fields); // don't need
        foreach ($fields as $key => $value) {
            $this->_statement->bindValue(':' . $key, $value, $this->bind($value));
        }
        return $this->_statement;
    }
    /**
     * 
     * @param array $fields
     * @return mixed
     * @throws BaseInvalidArgumentException
     */
    protected function bindSearchValues(array $fields): PDOStatement
    {
        $this->isArray($fields); // don't need
        foreach ($fields as $key => $value) {
            $this->_statement->bindValue(':' . $key,  '%' . $value . '%', $this->bind($value));
        }
        return $this->_statement;
    }
    /**
     * Summary of execute
     * @return bool
     */
    public function execute()
    {
        if ($this->_statement)
            return $this->_statement->execute();
    }
    /**
     * Summary of numRows
     * @return integer
     */
    public function numRows(): int
    {
        if ($this->_statement)
            return $this->_statement->rowCount();
    }

    /**
     * Summary of result
     * @return object|null
     */
    public function result(): ?Object
    {
        if ($this->_statement) {
            $result = $this->_statement->fetch(PDO::FETCH_OBJ);
            return $result === false ? null : $result;
        }
    }

   /**
     * @inheritDoc
     * @return array
     */
    public function results(): array
    {
        if ($this->_statement)
            return $this->_statement->fetchAll();
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function getLastId(): int
    {
        try {
            if ($this->connection->pdo()) {
                $lastID = $this->connection->pdo()->lastInsertId();
                if (!empty($lastID)) {
                    return intval($lastID);
                }
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Returns the query condition merged with the query parameters
     * 
     * @param array $conditions
     * @param array $parameters
     * @return array
     */
    public function buildQueryParameters(array $conditions = [], array $parameters = []): array
    {
        return (!empty($parameters) || (!empty($conditions)) ? array_merge($conditions, $parameters) : $parameters);
    }

    /**
     * Persist queries to database
     *
     * @param string $sqlQuery
     * @param array $parameters
     * @param bool $search defaults to false
     * @return void
     * @throws DataLayerException
     */
    public function persist(string $sqlQuery, array $parameters, bool $search = false): void
    {
       // $this->start();
        try {
            $this->prepare($sqlQuery)->bindParameters($parameters, $search)->execute();
           //$this->commit();
        } catch (PDOException $e) {
           // $this->revert();
           throw new PDOException('Data persistent error ' . $e->getMessage());
        }
    }
}
