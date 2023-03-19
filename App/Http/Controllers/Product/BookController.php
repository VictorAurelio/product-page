<?php

namespace App\Http\Controllers\Product;

use App\Controllers\Product\ProductController;
use App\Controllers\Product\ProductSpecificControllerInterface;
use App\Core\Validation\Exception\ValidationException;
use App\Database\DAO\ProductDAO;
use App\DTO\BookDTO;
use App\Models\Product\Book;

class BookController implements ProductSpecificControllerInterface
{
    protected Book $productModel;
    protected ProductController $productController;
    protected ProductDAO $productDAO;
    public function __construct(ProductController $productController)
    {
        $this->productController = $productController;
        $this->productModel = new Book($this->productController->getConnection());
        $this->productDAO = new ProductDAO($this->productModel);
    }
    public function insertProduct(array $data): array
    {
        $this->productController->getValidator()->validate($data, [
            'name' => ['required'],
            'sku' => ['required', 'unique'],
            'price' => ['required'],
            'category_id' => ['required'],
            'weight' => ['required'] // Add weight validation for Book
        ]);
    
        $bookDTO = $this->createBookDTO($data);
        $book = $this->productController->getProductDAO()->create($bookDTO);
    
        $result = match (true) {
            !$book => ['message' => 'Error creating book', 'status' => 500],
            default => ['message' => 'Book created successfully', 'status' => 201],
        };
        return $result;
    }
    public function insertBook(array $data): array
    {
        $data = $this->productController->getSanitizer()->clean($data);

        $this->productController->getValidator()->validate($data, [
            'name' => ['required'],
            'sku' => ['required'],
            'price' => ['required'],
            'category_id' => ['required'],
            'weight' => ['required'] // Add weight validation for Book
        ]);

        $bookDTO = $this->createBookDTO($data);
        $book = $this->productController->getProductDAO()->create($bookDTO);

        $result = match (true) {
            !$book => ['message' => 'Error creating book', 'status' => 500],
            default => ['message' => 'Book created successfully', 'status' => 201],
        };
        return $result;
    }
    public function createBookDTO(array $data): BookDTO
    {
        $bookDTO = new BookDTO();
        $bookDTO->setId($data['id'] ?? null);
        $bookDTO->setName($data['name']);
        $bookDTO->setSku($data['sku']);
        $bookDTO->setPrice($data['price']);
        $bookDTO->setCategoryId($data['category_id']);
        $bookDTO->setWeight($data['weight']);

        return $bookDTO;
    }
}