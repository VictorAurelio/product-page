<?php

/**
 * This file is part of my Product Page project.
 *
 * @category  Model
 * @package   App\Models\Category
 * @author    Victor Aurélio Rodrigues Ribeiro <victoraurelio_198@hotmail.com>
 * @copyright (c) 2023 Victor Aurelio
 * @link      https://github.com/VictorAurelio/product-page
 */

namespace App\Models\Category;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DAO\CategoryDAO;
use App\Core\Database\DAO\DAO;
use App\Core\Database\DatabaseService\DatabaseService;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
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
        $this->categoryDAO = new CategoryDAO($this);
    }
    public function getDao(): DAO
    {
        return new DAO(
            new DatabaseService($this->connection),
            new MysqlQueryBuilder($this->connection),
            self::TABLESCHEMA,
            self::TABLESCHEMAID
        );
    }
    public function store(DTOInterface $categoryDTO) {
        if (!$categoryDTO instanceof CategoryDTO) {
            throw new InvalidArgumentException('Expected CategoryDTO instance.');
        }
        return $this->categoryDAO->create($categoryDTO);
    }
}