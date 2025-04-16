<?php

declare(strict_types=1);

namespace MvpMarket\Models;

use MvpMarket\Database\QueryBuilder;
use PDOException;
use RuntimeException;
use Throwable;

class ProductModel extends DataModel
{
    protected string $productId;
    protected string $name;
    protected array $price;
    protected array $galleries = [];
    protected string $description;
    protected bool $inStock;
    protected string $category;
    protected string $brand;
    protected array $attributes = [];

    const SELECTS = ["p.id AS product_id", "p.name", "p.in_stock", "p.description", "p.category", "p.brand"];

    public function __construct(array $data = [])
    {
        error_log("[ProductModel] __construct() called with data: " . json_encode($data));
    
        try {
            parent::__construct(data: $data);
            $this->tableName = 'products p';
            $this->queryBuilder->setFrom($this->tableName);
            error_log("[ProductModel] Constructor complete for product ID: " . ($data['product_id'] ?? 'N/A'));
        } catch (Throwable $e) {
            error_log("[ProductModel ERROR] Failed to construct ProductModel: " . $e->getMessage());
            throw new RuntimeException("Failed to initialize product model.");
        }
    }
    
    public function getAll(): array
    {
        error_log("[ProductModel] getAll() called");

        try {
            $this->queryBuilder->clearSQL();
            $this->addToQuery($this->queryBuilder);
            GalleryModel::addToQuery($this->queryBuilder);
            PriceModel::addToQuery($this->queryBuilder);
            AttributeValueModel::addToQuery($this->queryBuilder);

            $this->queryBuilder->toSQL();
            error_log("[ProductModel] Final SQL: " . $this->queryBuilder->sql);

            $results = $this->queryBuilder->runSQL();
            error_log("[ProductModel] Retrieved " . count($results) . " rows");

            $products = [];

            foreach ($results as $row) {
                try {
                    $productId = $row['product_id'];

                    if (!isset($products[$productId])) {
                        $products[$productId] = new self($row);

                        try {
                            $products[$productId]->price = (new PriceModel($row))->toArray();
                        } catch (Throwable $e) {
                            error_log("[ProductModel ERROR] Failed to map PriceModel for product $productId: " . $e->getMessage());
                            throw new RuntimeException("Error mapping price for product $productId.");
                        }
                    }

                    if (!empty($row['gallery_id'])) {
                        try {
                            $gallery = new GalleryModel($row);
                            $products[$productId]->galleries[$row['gallery_id']] = $gallery->toArray();
                        } catch (Throwable $e) {
                            error_log("[ProductModel ERROR] Failed to map GalleryModel for product $productId: " . $e->getMessage());
                            throw new RuntimeException("Error mapping gallery for product $productId.");
                        }
                    }

                    if (!empty($row['attribute_value_id'])) {
                        try {
                            $attribute = new AttributeValueModel($row);
                            $products[$productId]->attributes[$row['attribute_value_id']] = [
                                'attributeValue' => $attribute->toArray()
                            ];
                        } catch (Throwable $e) {
                            error_log("[ProductModel ERROR] Failed to map AttributeValueModel for product $productId: " . $e->getMessage());
                            throw new RuntimeException("Error mapping attribute for product $productId.");
                        }
                    }
                } catch (Throwable $e) {
                    error_log("[ProductModel ERROR] Error building product object: " . $e->getMessage());
                }
            }

            foreach ($products as &$product) {
                try {
                    $product = $product->toArray();
                } catch (Throwable $e) {
                    error_log("[ProductModel ERROR] toArray failed for product: " . $e->getMessage());
                }
            }

            return $products;
        } catch (PDOException $e) {
            error_log("[ProductModel ERROR] DB failure in getAll: " . $e->getMessage());
            throw new RuntimeException("Unable to fetch products from the database.");
        } catch (Throwable $e) {
            error_log("[ProductModel ERROR] Unexpected error in getAll: " . $e->getMessage());
            throw new RuntimeException("Unexpected error occurred while fetching products.");
        }
    }

    public function getOne($id)
    {
        error_log("[ProductModel] getOne() called with ID: $id");

        try {
            $this->queryBuilder->clearSQL();
            $this->addToQuery($this->queryBuilder);
            GalleryModel::addToQuery($this->queryBuilder);
            PriceModel::addToQuery($this->queryBuilder);
            AttributeValueModel::addToQuery($this->queryBuilder);

            $this->queryBuilder->addWhere(condition: "p.id = '$id'");
            $this->queryBuilder->toSQL();
            error_log("[ProductModel] Final SQL for getOne: " . $this->queryBuilder->sql);

            $results = $this->queryBuilder->runSQL();
            error_log("[ProductModel] getOne() found " . count($results) . " matching rows");

            if (empty($results)) {
                return null;
            }

            try {
                $product = new self($results[0]);
                $product->price = (new PriceModel($results[0]))->toArray();
                $product->attributes = [];
                $product->galleries = [];

                foreach ($results as $row) {
                    if (!empty($row['gallery_id'])) {
                        try {
                            $gallery = new GalleryModel($row);
                            $product->galleries[$row['gallery_id']] = $gallery->toArray();
                        } catch (Throwable $e) {
                            error_log("[ProductModel ERROR] Failed to map GalleryModel for getOne($id): " . $e->getMessage());
                        }
                    }

                    if (!empty($row['attribute_value_id'])) {
                        try {
                            $attribute = new AttributeValueModel($row);
                            $product->attributes[$row['attribute_value_id']] = [
                                'attributeValue' => $attribute->toArray()
                            ];
                        } catch (Throwable $e) {
                            error_log("[ProductModel ERROR] Failed to map AttributeValueModel for getOne($id): " . $e->getMessage());
                        }
                    }
                }

                return $product->toArray();
            } catch (Throwable $e) {
                error_log("[ProductModel ERROR] Failed to build product object in getOne($id): " . $e->getMessage());
                throw new RuntimeException("Unable to map product object.");
            }
        } catch (PDOException $e) {
            error_log("[ProductModel ERROR] DB failure in getOne($id): " . $e->getMessage());
            throw new RuntimeException("Unable to fetch product from the database.");
        } catch (Throwable $e) {
            error_log("[ProductModel ERROR] Unexpected error in getOne($id): " . $e->getMessage());
            throw new RuntimeException("Unexpected error occurred while fetching product.");
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->productId,
            'name' => $this->name,
            'prices' => [$this->price],
            'description' => $this->description,
            'galleries' => $this->galleries,
            'inStock' => (bool) $this->inStock,
            'category' => $this->category,
            'brand' => $this->brand,
            'attributes' => $this->attributes
        ];
    }

    public static function addToQuery(QueryBuilder $qb)
    {
        foreach (self::SELECTS as $select) {
            $qb->addSelect($select);
        }
    }
}
