<?php

/**
 * This file is part of my Product Page project.
 *
 * @category  DAO
 * @package   App\Core\Database\DAO
 * @author    Victor Aurélio Rodrigues Ribeiro <victoraurelio_198@hotmail.com>
 * @copyright (c) 2023 Victor Aurelio
 * @link      https://github.com/VictorAurelio/starter-mvc
 */

namespace App\Core\Database\DAO;

use App\Core\Database\QueryBuilder\QueryBuilder;
use App\Core\Database\DatabaseService\DatabaseService;
use Throwable;

/**
 * Summary of DAO
 */
class DAO
{
    /**
     * @var QueryBuilder 
     */
    protected QueryBuilder $queryBuilder;

    /**
     * @var DataMapper 
     */
    protected DatabaseService $dataMapper;

    /**
     * @var string 
     */
    protected string $tableSchemaID;

    /**
     * @var string 
     */
    protected string $tableSchema;

    /**
     * @var array 
     */
    protected array $options;

    /**
     * Main constructor
     *
     * @param DatabaseService   $dataMapper
     * @param QueryBuilder $queryBuilder
     * @param string       $tableSchema
     * @param string       $tableSchemaID
     * @param array|null   $options
     */
    public function __construct(
        DatabaseService $dataMapper,
        QueryBuilder $queryBuilder,
        string $tableSchema,
        string $tableSchemaID,
        ?array $options = []
    ) {
        $this->tableSchemaID = $tableSchemaID;
        $this->queryBuilder = $queryBuilder;
        $this->tableSchema = $tableSchema;
        $this->dataMapper = $dataMapper;
        $this->options = $options;
    }

    /**
     * Summary of GetSchema
     * 
     * @inheritdoc
     *
     * @return string
     */
    public function getSchema(): string
    {
        return (string)$this->tableSchema;
    }

    /**
     * Summary of GetSchemaId
     *
     * @inheritdoc
     *
     * @return string
     */
    public function getSchemaID(): string
    {
        return (string)$this->tableSchemaID;
    }

    /**
     * Summary of LastId
     * 
     * @inheritdoc
     *
     * @return integer
     */
    public function lastID(): int
    {
        return $this->dataMapper->getLastId();
    }

    /**
     * Summary of Where
     *
     * @param array $conditions
     * 
     * @return DAO
     */
    public function where(array $conditions = []): self
    {
        $args = [
            'table' => $this->getSchema(),
            'type' => 'select',
            'selectors' => [],
            'conditions' => $conditions,
            'params' => []
        ];
        $this->queryBuilder->buildQuery($args)->selectQuery();
        return $this;
    }

    /**
     * Summary of FindByExact
     *
     * @param array $fields
     * 
     * @return object|null
     */
    public function findByExact(array $fields): ?Object
    {
        $sqlQuery = $this->queryBuilder->buildQuery(
            [
                'type' => 'search',
                'selectors' => $fields,
                'table' => $this->getSchema()
            ]
        )->exactSearchQuery();

        $this->dataMapper->persist(
            $sqlQuery,
            $this->dataMapper->buildQueryParameters([], $fields),
            false
        );
        return $this->dataMapper->result();
    }

    /**
     * Summary of Create
     * 
     * @param array $fields
     * 
     * @inheritdoc
     * 
     * @return boolean
     */
    public function create(array $fields = []): bool
    {
        try {
            $args = [
                'table' => $this->getSchema(),
                'type' => 'insert',
                'fields' => $fields
            ];
            $query = $this->queryBuilder->buildQuery($args)->insertQuery();
            $this->dataMapper->persist(
                $query,
                $this->dataMapper->buildQueryParameters($fields)
            );

            if ($this->dataMapper->numRows() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }


    /**
     * Summary of read
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
                'table' => $this->getSchema(),
                'type' => 'select',
                'selectors' => $selectors,
                'conditions' => $conditions,
                'params' => $parameters,
                'extras' => $optional
            ];
            $query = $this->queryBuilder->buildQuery($args)->selectQuery();
            $this->dataMapper->persist(
                $query,
                $this->dataMapper->buildQueryParameters($conditions, $parameters)
            );
            if ($this->dataMapper->numRows() > 0) {
                return $this->dataMapper->results();
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * Summary of Update
     * 
     * @param string $primaryKey
     * @param array  $fields
     * 
     * @inheritdoc
     * 
     * @return boolean
     */
    public function update(array $fields = [], string $primaryKey): bool
    {
        try {
            $args = [
                'table' => $this->getSchema(),
                'type' => 'update',
                'fields' => $fields,
                'primary_key' => $primaryKey
            ];
            $query = $this->queryBuilder->buildQuery($args)->updateQuery();
            $this->dataMapper->persist(
                $query,
                $this->dataMapper->buildQueryParameters($fields)
            );
            if ($this->dataMapper->numRows() === 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Summary of Delete
     * 
     * @param array $conditions
     * 
     * @inheritdoc
     * 
     * @return boolean
     */
    public function delete(array $conditions = []): bool
    {
        try {
            $args = [
                'table' => $this->getSchema(),
                'type' => 'delete',
                'conditions' => $conditions
            ];
            $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
            $this->dataMapper->persist(
                $query,
                $this->dataMapper->buildQueryParameters($conditions)
            );
            if ($this->dataMapper->numRows() === 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Summary of Search
     *
     * @param array $selectors
     * @param array $conditions
     * @param bool  $exact
     * 
     * @inheritdoc
     * 
     * @return array
     * 
     * @throws DataLayerException
     */
    public function search(
        array $selectors = [],
        array $conditions = [],
        bool $exact = false
    ): array {
        $args = [
            'table' => $this->getSchema(),
            'type' => 'search',
            'selectors' => $selectors,
            'conditions' => $conditions,
            'isSearch' => !$exact
        ];
        $query = $exact ?
            $this->queryBuilder->buildQuery($args)->searchQueryExact() :
            $this->queryBuilder->buildQuery($args)->searchQuery();

        $this->dataMapper->persist(
            $query,
            $this->dataMapper->buildQueryParameters($conditions),
            true
        );
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->results() : array();
    }
    /**
     * Summary of RawQuery
     * 
     * @param string     $rawQuery
     * @param array|null $conditions
     * 
     * @inheritdoc 
     * 
     * @return void
     */
    public function rawQuery(string $rawQuery, ?array $conditions = [])
    {
        try {
            $args = [
                'table' => $this->getSchema(),
                'type' => 'raw',
                'raw' => $rawQuery,
                'conditions' => $conditions
            ];
            $query = $this->queryBuilder->buildQuery($args)->rawQuery();
            $this->dataMapper->persist(
                $query,
                $this->dataMapper->buildQueryParameters($conditions)
            );
            if ($this->dataMapper->numRows()) {
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Summary of GetQueryType
     * 
     * @param string $type
     * 
     * @return mixed
     */
    public function getQueryType(string $type)
    {
        $queryTypes = [
            'createQuery',
            'readQuery',
            'updateQuery',
            'deleteQuery',
            'joinQuery',
            'searchQuery',
            'rawQuery'
        ];
        if (!empty($type)) {
            if (in_array($type, $queryTypes, true)) {
                return $this->$type;
            }
        }
    }
}
