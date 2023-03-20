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
use App\DTO\OptionDTO;
use App\DTO\ProductOptionDTO;
use App\Models\Category\Category;
use App\Models\Option\Option;
use App\Models\Product\Book;
use App\Models\Product\Dvd;
use App\Models\Product\Furniture;
use App\Models\Product\Product;
use App\Core\Database\DAO\DAO;
use App\Core\Controller;
use App\Core\Validation\Rule\ExistRule;
use App\Core\Validation\Rule\RequiredIfRule;
use App\Core\Validation\Rule\UniqueRule;
use App\Models\ProductOption\ProductOption;
use InvalidArgumentException;

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
            ->addRule('required_if', new RequiredIfRule())
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
        // var_dump($payload);
        try {
            // Validate the necessary data
            $this->validator->validate($data, [
                'product_type' => ['required', 'in:Book,Dvd,Furniture'],
                'category_id' => ['required', 'exist:categories,id'],
                'weight' => ['required_if:product_type,Book'],
                'size_in_mb' => ['required_if:product_type,Dvd'],
                'dimensions' => ['required_if:product_type,Furniture'],
            ]);

            // Store or update the category
            $categoryModel = new Category($this->getConnection());
            $categoryDTO = new CategoryDTO();
            $categoryDTO->setId($data['category_id']);
            $categoryModel->store($categoryDTO);

            // Insert the product
            $productController = $this->getControllerInstance($data['product_type']);
            $result = $productController->insertProduct($data);

            $productId = $result['id'];
            $categoryId = $data['category_id'];

            // Get the ID of the corresponding option for the product type
            $optionId = $this->getOptionIdByType($data['product_type']);

            // Save the selected option for this product
            $productOptionDTO = new ProductOptionDTO();
            $productOptionDTO->setProductId($productId);
            $productOptionDTO->setOptionId($optionId);
            $productOptionDTO->setOptionValue($data['option_value']);

            $productOption = new ProductOption($this->getConnection());
            $productOption->createOption($productOptionDTO);

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
            'Book' => new Book($this->getConnection()),
            'Dvd' => new Dvd($this->getConnection()),
            'Furniture' => new Furniture($this->getConnection()),
            'category' => new Category($this->getConnection()),
            default => throw new InvalidArgumentException("Invalid product type"),
        };

        $class = match ($type) {
            'Book' => BookController::class,
            'Dvd' => DvdController::class,
            'Furniture' => FurnitureController::class,
            'category' => CategoryController::class,
            default => throw new InvalidArgumentException("Invalid product type"),
        };
        return new $class($this, $productModel);
    }
    public function getOptionIdByType(string $type): int
    {
        switch ($type) {
            case 'Book':
                return 1; // ID da opção de peso (kg) para book
            case 'Dvd':
                return 2; // ID da opção de tamanho (MB) para DVDs
            case 'Furniture':
                return 3; // ID da opção de dimensões (HxWxL) para furniture
            default:
                throw new InvalidArgumentException('Invalid product type.');
        }
    }
    public function insertOption(array $data): int
    {
        $data = $this->sanitizer->clean($data);

        $this->validator->validate($data, [
            'option_name' => ['required'],
            'option_value' => ['required'],
        ]);

        $optionModel = new Option($this->getConnection());
        $optionDTO = new OptionDTO($data['option_id'], $data['option_name']);
        $optionDTO->setName($data['option_name']);

        $optionId = $optionModel->store($optionDTO);

        $productOptionDTO = new ProductOptionDTO();
        $productOptionDTO->setProductId($data['product_id']);
        $productOptionDTO->setOptionId($optionId);
        $productOptionDTO->setOptionValue($data['option_value']);

        $productOption = new ProductOption($this->getConnection());
        $productOption->createOption($productOptionDTO);

        return $optionId;
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
