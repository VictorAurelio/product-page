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

use App\Http\Controllers\Product\ProductSpecificControllerInterface;
use App\Http\Controllers\Product\ProductController;
use App\Models\ProductOption\ProductOption;
use App\Core\Database\DAO\Product\BookDAO;
use App\DTO\Product\ProductOptionDTO;
use App\DTO\Product\BookDTO;
use App\Models\Product\Book;
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
        // Get the ID of the corresponding option for the product type
        $optionId = $this->productController->getOptionIdByType('Book');    
        $bookDTO = $this->createDTO($data, $data['weight']);

        // Create the book and get the last inserted book ID
        $book = $this->bookDAO->create($bookDTO);
        
        $productOptionDTO = new ProductOptionDTO();
        $productOptionDTO->setProductId($book);
        $productOptionDTO->setOptionId($optionId);
        $productOptionDTO->setOptionValue($data['weight']);

        $productOption = new ProductOption($this->productController->getConnection());        
        $productOption->createOption($productOptionDTO);

        $result = match (true) {
            !$book => ['message' => 'Error creating book', 'status' => 500],
            default => ['message' => 'Book created successfully', 'status' => 201],
        };
        return $result;
    }
    public function createDTO(array $data, $optionValue): DTOInterface
    {
        $bookDTO = new BookDTO();
        $bookDTO->setId($data['id'] ?? null);
        $bookDTO->setName($data['name']);
        $bookDTO->setSku($data['sku']);
        $bookDTO->setPrice($data['price']);
        $bookDTO->setCategoryId($data['category_id']);
        $bookDTO->setWeight($optionValue);

        return $bookDTO;
    }
    public function getDAO(): BookDAO
    {
        return $this->bookDAO;
    }
}