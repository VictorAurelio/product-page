<?php

/**
 * This file is part of my Product Page project.
 *
 * @category  Controller
 * @package   App\Http\Controllers\Product
 * @author    Victor Aurélio Rodrigues Ribeiro <victoraurelio_198@hotmail.com>
 * @copyright (c) 2023 Victor Aurelio
 * @link      https://github.com/VictorAurelio/product-page
 */

namespace App\Http\Controllers\Product;

use App\Controllers\Product\ProductSpecificControllerInterface;
use App\Controllers\Product\ProductController;
use App\Database\DAO\Product\BookDAO;
use App\Models\Product\Book;
use App\DTO\BookDTO;
use App\DTO\DTOInterface;

class BookController implements ProductSpecificControllerInterface
{
    protected ProductController $productController;
    protected Book $productModel;
    protected BookDAO $bookDAO;
    public function __construct(ProductController $productController, Book $bookModel)
    {
        $this->productController = $productController;
        $this->productModel = new Book($this->productController->getConnection());
        $this->bookDAO = new BookDAO($bookModel);
    }
    public function insertProduct(array $data): array
    {
        $data = $this->productController->getSanitizer()->clean($data);

        $this->productController->getValidator()->validate($data, [
            'name' => ['required'],
            'sku' => ['required', 'unique'],
            'price' => ['required'],
            'category_id' => ['required'],
            'weight' => ['required']
        ]);
    
        $bookDTO = $this->createDTO($data);
        $book = $this->bookDAO->create($bookDTO);
    
        $result = match (true) {
            !$book => ['message' => 'Error creating book', 'status' => 500],
            default => ['message' => 'Book created successfully', 'status' => 201],
        };
        return $result;
    }
    public function createDTO(array $data): DTOInterface
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