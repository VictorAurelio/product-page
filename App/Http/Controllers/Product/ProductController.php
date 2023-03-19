<?php

namespace App\Controllers\Product;

use App\Core\Database\DatabaseService\DatabaseService;
use App\Core\Validation\Exception\ValidationException;
use App\Core\Database\Connection\ConnectionInterface;
use App\Core\Database\QueryBuilder\MysqlQueryBuilder;
use App\Core\Validation\Rule\Data\DataSanitizer;
use App\Core\Database\DatabaseFactory;
use App\Core\Validation\Rule\InRule;
use App\Core\Validation\Rule\RequiredRule;
use App\Core\Validation\Validator;
use App\Database\DAO\ProductDAO;
use App\Models\Product\Product;
use App\Core\Database\DAO\DAO;
use App\Core\Controller;
use App\Core\Validation\Rule\UniqueRule;
use App\DTO\ProductDTO;

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
            new DatabaseService($this->connection),
            new MysqlQueryBuilder($this->connection),
            Product::TABLESCHEMA,
            Product::TABLESCHEMAID
        );
        $this->validator = new Validator();
        $this->validator
            ->addRule('unique', new UniqueRule($this->connection, Product::TABLESCHEMA, 'sku'))
            ->addRule('required', new RequiredRule())
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
                'type' => ['required', 'in:book,dvd,furniture']
            ]);

            $productController = $this->getControllerInstance($data['type']);
            $result = $productController->insertProduct($data);
            $this->json($result['message'], $result['status']);
        } catch (ValidationException $e) {
            $this->json($e->getErrors(), 400);
        } catch (\InvalidArgumentException $e) {
            $this->json(['message' => $e->getMessage()], 400);
        }
    }

    public function createProductDTO(array $data): ProductDTO
    {
        $productDTO = new ProductDTO();
        $productDTO->setName($data['name']);
        $productDTO->setSku($data['sku']);
        $productDTO->setPrice($data['price']);
        $productDTO->setCategoryId($data['category_id']);

        return $productDTO;
    }
    private function getControllerInstance(string $type): ProductSpecificControllerInterface
    {
        $class = match ($type) {
            'book' => BookController::class,
            'dvd' => DvdController::class,
            'furniture' => FurnitureController::class,
            default => throw new \InvalidArgumentException("Invalid product type"),
        };

        return new $class($this);
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
