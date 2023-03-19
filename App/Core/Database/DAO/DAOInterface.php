<?php

/**
 * This file is part of my Product Page project.
 *
 * @category  Interface
 * @package   App\Core\Database\DAO
 * @author    Victor Aurélio Rodrigues Ribeiro <victoraurelio_198@hotmail.com>
 * @copyright (c) 2023 Victor Aurelio
 * @link      https://github.com/VictorAurelio/product-page
 */

namespace App\Core\Database\DAO;

use App\DTO\DTOInterface;

/**
 * ConnectionInterface defines the contract for DAO class.
 */
interface DAOInterface
{
    public function create(DTOInterface $data): bool;
    public function read(
        array $selectors = [],
        array $conditions = [],
        array $parameters = [],
        array $optional = []
    ): array;
    public function update(DTOInterface $data, string $primaryKey): bool;
    public function delete(DTOInterface $conditions): bool;
    public function rawQuery(string $rawQuery, DTOInterface $conditions);
}
