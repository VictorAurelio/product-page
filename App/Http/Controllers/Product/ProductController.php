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
use App\Core\Validation\Exception\ValidationException;
use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Core\Validation\Rule\Data\DataSanitizer;
use App\Core\Database\DAO\Product\ProductDAO;
use App\Core\Validation\Rule\RequiredIfRule;
use App\Models\ProductOption\ProductOption;
use App\Core\Validation\Rule\RequiredRule;
use App\Core\Validation\Rule\UniqueRule;
use App\Core\Validation\Rule\ExistRule;
use App\Core\Database\DatabaseFactory;
use App\DTO\Product\ProductOptionDTO;
use App\Core\Validation\Rule\InRule;
use App\Core\Validation\Validator;
use App\Models\Category\Category;
use App\Models\Product\Furniture;
use App\Models\Product\Product;
use App\Core\Database\DAO\DAO;
use InvalidArgumentException;
use App\Models\Product\Book;
use App\Models\Product\Dvd;
use App\Core\Controller;

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
        try {
            // Validate the necessary data
            $this->validator->validate($data, [
                'product_type' => ['required', 'in:Book,Dvd,Furniture'],
                'weight' => ['required_if:product_type,Book'],
                'size' => ['required_if:product_type,Dvd'],
                'dimensions' => ['required_if:product_type,Furniture'],
            ]);
            // Insert the product
            $productController = $this->getControllerInstance($data['product_type']);
            
            $data['category_id'] = match ($data['product_type']) {
                'Book' => 1,
                'Dvd' => 2,
                'Furniture' => 3,
                default => throw new InvalidArgumentException("Invalid product type"),
            };
            $result = $productController->insertProduct($data);

            if (is_array($result) && isset($result['id'])) {
                $productId = $result['id'];
        
                // Get the ID of the corresponding option for the product type
                $optionId = $this->getOptionIdByType($data['product_type']);
        
                // Get the option value based on the product type
                $optionValueKey = match ($data['product_type']) {
                    'Book' => 'weight',
                    'Dvd' => 'size_in_mb',
                    'Furniture' => 'dimensions',
                    default => throw new InvalidArgumentException('Invalid product type'),
                };
        
                // Save the selected option for this product
                $productOptionDTO = new ProductOptionDTO();
                $productOptionDTO->setProductId($productId);
                $productOptionDTO->setOptionId($optionId);
                $productOptionDTO->setOptionValue($data[$optionValueKey]);
        
                $productOption = new ProductOption($this->getConnection());
                $productOption->createOption($productOptionDTO);
        
                // $this->json($result['message'], $result['status']);
            }
            $this->json(['message' => $result['message']], $result['status']);
        } catch (ValidationException $e) {
            $this->json($e->getErrors(), 400);
        } catch (InvalidArgumentException $e) {
            $this->json(['message' => $e->getMessage()], 400);
        }
    }
    private function getControllerInstance(string $type): ProductSpecificControllerInterface
    {
        $productModel = match ($type) {
            'Book' => new Book($this->getConnection()),
            'Dvd' => new Dvd($this->getConnection()),
            'Furniture' => new Furniture($this->getConnection()),
            default => throw new InvalidArgumentException("Invalid product type"),
        };

        $class = match ($type) {
            'Book' => BookController::class,
            'Dvd' => DvdController::class,
            'Furniture' => FurnitureController::class,
            default => throw new InvalidArgumentException("Invalid product type"),
        };
        return new $class($this, $productModel);
    }
    public function getOptionIdByType(string $type): int
    {
        return match ($type) {
            'Book' => 1, // Weight in (kg) ID for book
            'Dvd' => 2, // Size in (MB) ID for DVDs
            'Furniture' => 3, // Dimensions in (HxWxL) ID for furniture
            default => throw new InvalidArgumentException('Invalid product type.'),
        };
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
