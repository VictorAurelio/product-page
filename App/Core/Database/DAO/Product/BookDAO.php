<?php

namespace App\Database\DAO\Product;

use App\DTO\DTOInterface;
use App\DTO\BookDTO;
use App\Models\Product\Book;
use InvalidArgumentException;
use Throwable;

class BookDAO extends ProductDAO
{
    protected Book $bookModel;
    public function __construct(Book $bookModel)
    {
        parent::__construct($bookModel);
        $this->bookModel = $bookModel;
    }
    /**
     * This method receives a BookDTO with the data necessary to create a new book
     * and creates a new record in the database. It returns true if the creation
     * was successful and false otherwise.
     *
     * @param DTOInterface $data
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
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
    /**
     * This method receives a BookDTO with the data necessary to update a book
     * and an ID representing the book to be updated. It updates the book record
     * in the database and returns true if the update was successful and false
     * otherwise.
     *
     * @param DTOInterface $data
     * @param string $primaryKey
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function update(DTOInterface $data, string $primaryKey): bool
    {
        if (!$data instanceof BookDTO) {
            throw new InvalidArgumentException('Expected BookDTO instance.');
        }
    
        // Convert BookDTO to array
        $fields = $data->toArray();
    
        // Merge specific attributes of Book with the Product attributes
        $mergedFields = array_merge($fields, $this->bookModel->specificAttributes($data));
    
        try {
            $args = [
                'table' => $this->dao->getSchema(),
                'type' => 'update',
                'fields' => $mergedFields,
                'primary_key' => $primaryKey
            ];
            $query = $this->dao->getQueryBuilder()->buildQuery($args)->updateQuery();
            $this->dao->getDataMapper()->persist(
                $query,
                $this->dao->getDataMapper()->buildQueryParameters($mergedFields)
            );
            if ($this->dao->getDataMapper()->numRows() === 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    
        return false;
    }
    
}
