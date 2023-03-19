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

namespace App\Controllers\Product;

use App\Core\Database\DAO\Product\ProductCategoryDAO;
use App\Core\Database\DatabaseService\DatabaseService;
use App\Core\Model\ProductCategory;
use App\Core\Validation\Exception\ValidationException;
use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Core\Validation\Rule\Data\DataSanitizer;
use App\Core\Database\DatabaseFactory;
use App\Core\Validation\Rule\InRule;
use App\Core\Validation\Rule\RequiredRule;
use App\Core\Validation\Validator;
use App\Database\DAO\Product\ProductDAO;
use App\DTO\CategoryDTO;
use App\Models\Category\Category;
use App\Models\Product\Book;
use App\Models\Product\Dvd;
use App\Models\Product\Furniture;
use App\Models\Product\Product;
use App\Core\Database\DAO\DAO;
use App\Core\Controller;
use App\Core\Model\ProductCategoryModel;
use App\Core\Validation\Rule\ExistRule;
use App\Core\Validation\Rule\UniqueRule;

class ProductController extends Controller
{
    protected ConnectionInterface $connection;
    protected ProductDAO $productDAO;
    protected DataSanitizer $sanitizer;
    protected Validator $validator;
    protected DAO $dao;
    public function __construct()
    {
        $this->connection = DatabaseFactory::createConnection();
        $this->sanitizer = new DataSanitizer();

        $this->dao = new DAO(
            new DatabaseService($this->getConnection()),
            new MysqlQueryBuilder($this->getConnection()),
            Product::TABLESCHEMA,
            Product::TABLESCHEMAID
        );
        $this->validator = new Validator();
        $this->validator
            ->addRule('unique', new UniqueRule($this->getConnection(), Product::TABLESCHEMA, 'sku'))
            ->addRule('required', new RequiredRule())
            ->addRule('exist', new ExistRule($this->getConnection()))
            ->addRule('in', new InRule());
    }
    public function index()
    {
        echo 'product';
    }
    public function insertProduct()
    {
        if ($this->getMethod() !== 'POST') {
            $this->json(['message' => 'Invalid method for inserting product'], 405);
        }

        // Read the request data
        $payload = $this->getRequestData();
        $data = $this->sanitizer->clean($payload);

        try {
            // Validate the 'type' field!
            $this->validator->validate($data, [
                'type' => ['required', 'in:book,dvd,furniture'],
                'category_id' => ['required', 'exist:categories,id'],
                'option_id' => ['required', 'exist:options,id'],
            ]);

             // Store or update the category
            $categoryModel = new Category($this->getConnection());
            $categoryDTO = new CategoryDTO();
            $categoryDTO->setId($data['category_id']);
            $categoryModel->store($categoryDTO);

            // Insert the product and associate it with the category
            $productController = $this->getControllerInstance($data['type']);
            $result = $productController->insertProduct($data);

            $productId = $result['id'];
            $categoryId = $data['category_id'];

            $productCategoryDAO = new ProductCategory($this->getConnection());
            $productCategoryDAO->createProductCategory($productId, $categoryId);

            $this->json($result['message'], $result['status']);
        } catch (ValidationException $e) {
            $this->json($e->getErrors(), 400);
        } catch (\InvalidArgumentException $e) {
            $this->json(['message' => $e->getMessage()], 400);
        }
    }
    private function getControllerInstance(string $type): ProductSpecificControllerInterface
    {
        $productModel = match ($type) {
            'book' => new Book($this->getConnection()),
            'dvd' => new Dvd($this->getConnection()),
            'furniture' => new Furniture($this->getConnection()),
            'category' => new Category($this->getConnection()),
            default => throw new \InvalidArgumentException("Invalid product type"),
        };
    
        $class = match ($type) {
            'book' => BookController::class,
            'dvd' => DvdController::class,
            'furniture' => FurnitureController::class,
            'category' => CategoryController::class,
            default => throw new \InvalidArgumentException("Invalid product type"),
        };    
        return new $class($this, $productModel);
    }
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
    public function getProductDAO(): ProductDAO
    {
        return $this->productDAO;
    }
    public function getValidator(): Validator
    {
        return $this->validator;
    }
    public function getSanitizer(): DataSanitizer
    {
        return $this->sanitizer;
    }
}
