<?php

namespace App\Database\DAO;

use App\Core\Database\DAO\DAO;
use App\Core\Database\DAO\DAOInterface;
use App\DTO\DTOInterface;
use App\DTO\ProductDTO;
use App\Models\Product\Product;
use InvalidArgumentException;
use Throwable;

class ProductDAO implements DAOInterface
{
    protected DAO $dao;
    public function __construct(Product $productModel)
    {
        $this->dao = $productModel->getDao();
    }

    public function create(DTOInterface $data): bool
    {
        if (!$data instanceof ProductDTO) {
            throw new InvalidArgumentException('Expected ProductDTO instance.');
        }
        try {
            // Convert ProductDTO to array
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
}
