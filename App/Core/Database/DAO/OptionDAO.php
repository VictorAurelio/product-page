<?php

namespace App\Core\Database\DAO;

use App\Core\Database\DAO\DAOInterface;
use App\DTO\DTOInterface;

/**
 * Summary of UserDAO
 */
// App/Core/Database/DAO/UserDAO.php
class OptionDAO implements DAOInterface
{


    public function create(DTOInterface $data): bool
    {
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