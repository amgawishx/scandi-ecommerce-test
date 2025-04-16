<?php

declare(strict_types=1);

namespace MvpMarket\Models;

use MvpMarket\Database\QueryBuilder;
use PDOException;
use RuntimeException;

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
        parent::__construct(data: $data);
        $this->tableName = 'products p';
        $this->queryBuilder->setFrom($this->tableName);
    }

    public function getAll(): array
    {
        error_log("[ProductModel] getAll() called");

        try {
            $this->queryBuilder->clearSQL();
            error_log("[ProductModel] Cleared query builder");

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
                $productId = $row['product_id'];

                if (!isset($products[$productId])) {
                    $products[$productId] = new self($row);
                    $products[$productId]->price = (new PriceModel($row))->toArray();
                }

                if (!empty($row['gallery_id'])) {
                    $gallery = new GalleryModel($row);
                    $products[$productId]->galleries[$row['gallery_id']] = $gallery->toArray();
                }

                if (!empty($row['attribute_value_id'])) {
                    $attribute = new AttributeValueModel($row);
                    $products[$productId]->attributes[$row['attribute_value_id']] = [
                        'attributeValue' => $attribute->toArray()
                    ];
                }
            }

            foreach ($products as &$product) {
                $product = $product->toArray();
            }

            return $products;

        } catch (PDOException $e) {
            error_log("[ProductModel ERROR] getAll failed: " . $e->getMessage());
            throw new RuntimeException("Unable to fetch products.");
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

            $product = new self($results[0]);
            $product->price = (new PriceModel($results[0]))->toArray();
            $product->attributes = [];
            $product->galleries = [];

            foreach ($results as $row) {
                if (!empty($row['gallery_id'])) {
                    $gallery = new GalleryModel($row);
                    $product->galleries[$row['gallery_id']] = $gallery->toArray();
                }

                if (!empty($row['attribute_value_id'])) {
                    $attribute = new AttributeValueModel($row);
                    $product->attributes[$row['attribute_value_id']] = [
                        'attributeValue' => $attribute->toArray()
                    ];
                }
            }

            return $product->toArray();

        } catch (PDOException $e) {
            error_log("[ProductModel ERROR] getOne($id) failed: " . $e->getMessage());
            throw new RuntimeException("Unable to fetch product with ID: $id");
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
