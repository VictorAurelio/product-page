<?php

namespace App\Database\DAO;

use App\DTO\DTOInterface;
use App\DTO\BookDTO;
use App\Models\Product\Book;
use App\Models\Product\Furniture;
use InvalidArgumentException;
use Throwable;

class FurnitureDAO extends ProductDAO
{
    public function __construct(Furniture $furnitureModel)
    {
        parent::__construct($furnitureModel);
    }

    public function create(DTOInterface $data): bool
    {
        if (!$data instanceof BookDTO) {
            throw new InvalidArgumentException('Expected BookDTO instance.');
        }
        try {
            // Convert BookDTO to array
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

    // Implement other methods like update, delete, and find as necessary, similar to the create method
}
