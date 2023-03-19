<?php

namespace App\Models\Option;

use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\DAO\DAO;
use App\Core\Database\DAO\OptionDAO;
use App\Core\Database\DatabaseService\DatabaseService;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Core\Model;
use App\DTO\OptionDTO;

class Option extends Model
{
    protected const TABLESCHEMA = 'options';
    protected const TABLESCHEMAID = 'id';
    protected OptionDAO $optionDAO;

    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, $connection);
        $this->optionDAO = new OptionDAO($this);
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

    public function createOption(OptionDTO $optionDTO): bool
    {
        return $this->optionDAO->create($optionDTO);
    }
}
