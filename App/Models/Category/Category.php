<?php

namespace App\Models\Category;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DAO\CategoryDAO;
use App\Core\Model;
use App\DTO\CategoryDTO;
use App\DTO\DTOInterface;
use InvalidArgumentException;

class Category extends Model
{
    protected const TABLESCHEMA = 'categories';
    protected const TABLESCHEMAID = 'id';
    protected CategoryDAO $categoryDAO;

    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, $connection);
        $this->categoryDAO = new CategoryDAO();
    }

    public function store(DTOInterface $categoryDTO) {
        if (!$categoryDTO instanceof CategoryDTO) {
            throw new InvalidArgumentException('Expected CategoryDTO instance.');
        }
        return $this->categoryDAO->create($categoryDTO);
    }
}