<?php

declare(strict_types=1);


namespace MvpMarket\Models;
use MvpMarket\Database\QueryBuilder;
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
        $this->queryBuilder->clearSQL();
        $this->addToQuery($this->queryBuilder);
        GalleryModel::addToQuery($this->queryBuilder);
        PriceModel::addToQuery($this->queryBuilder);
        AttributeValueModel::addToQuery($this->queryBuilder);
        $this->queryBuilder->toSQL();
        $results = $this->queryBuilder->runSQL();
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
    }

    public function getOne($id)
    {
        $this->queryBuilder->clearSQL();
        $this->addToQuery($this->queryBuilder);
        GalleryModel::addToQuery($this->queryBuilder);
        PriceModel::addToQuery($this->queryBuilder);
        AttributeValueModel::addToQuery($this->queryBuilder);
        $this->queryBuilder->addWhere(condition: "p.id = '$id'");
        $this->queryBuilder->toSQL();
        $results = $this->queryBuilder->runSQL();
    
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