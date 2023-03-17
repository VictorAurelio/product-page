<?php

/**
 * This file is part of my Product Page project.
 *
 * @category  Interface
 * @package   App\Core\Database\DAO
 * @author    Victor Aurélio Rodrigues Ribeiro <victoraurelio_198@hotmail.com>
 * @copyright (c) 2023 Victor Aurelio
 * @link      https://github.com/VictorAurelio/starter-mvc
 */

namespace App\Core\Database\DAO;

/**
 * ConnectionInterface defines the contract for DAO class.
 */
interface DAOInterface
{
    /**
     * Summary of getSchema
     *
     * @return string
     */
    public function getSchema(): string;

    /**
     * Summary of getSchemaID
     *
     * @return string
     */
    public function getSchemaID(): string;

    /**
     * Summary of lastID
     *
     * @return int
     */
    public function lastID(): int;

    /**
     * Summary of create
     *
     * @param array $fields
     * 
     * @return bool
     */
    public function create(array $fields = []): bool;

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
    ): array;

    /**
     * Summary of update
     *
     * @param array  $fields
     * @param string $primaryKey
     * 
     * @return bool
     */
    public function update(array $fields = [], string $primaryKey): bool;

    /**
     * Summary of delete
     *
     * @param array $conditions
     * 
     * @return bool
     */
    public function delete(array $conditions = []): bool;

    /**
     * Summary of search
     *
     * @param array $selectors
     * @param array $conditions
     * 
     * @return array
     */
    public function search(array $selectors = [], array $conditions = []): array;

    /**
     * Summary of rawQuery
     *
     * @param string $rawQuery
     * @param array  $conditions
     * 
     * @return mixed 
     */
    public function rawQuery(string $rawQuery, array $conditions = []);
}
