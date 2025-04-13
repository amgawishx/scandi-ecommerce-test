<?php

declare(strict_types=1);

namespace MvpMarket\Models;

use MvpMarket\utility\Validator;


class OrderModel extends DataModel implements Validator
{
    protected string $productId;
    protected string $name;
    protected float $price;
    protected int $quantity;
    protected string $image;
    protected array $selectedAttributes = [];

    protected array $items;

    public function __construct(array $data = [])
    {
        parent::__construct(data: $data);
        $this->tableName = 'orders';
        $this->queryBuilder->setFrom($this->tableName);
    }

    public function toArray(): array
    {
        return [
            'productId' => $this->productId,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'image' => $this->image,
            'name' => $this->name,
            'selectedAttributes' => $this->selectedAttributes,
        ];
    }

    public function placeOrder(array $items = [])
    {
        foreach ($items as $item) {
            $order = new self($item);
            assert($order->validate());
    
            $order->queryBuilder->clearSQL();
            $order->queryBuilder->insertData([
                'product_id' => $item['productId'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'image' => $item['image'],
                'name' => $item['name'],
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $order->queryBuilder->runSQL();
    
            $orderId = $order->queryBuilder->getConnection()->lastInsertId();
    
            if (!empty($item['selectedAttributes']) && is_array($item['selectedAttributes'])) {
                foreach ($item['selectedAttributes'] as $attribute) {
                    $order->queryBuilder->setFrom("order_attribute_values");
                    $order->queryBuilder->insertData([
                        'order_id' => $orderId,
                        'attribute_value_id' => $attribute['id']
                    ]);
                    $order->queryBuilder->runSQL();
                }
            }
        }
    }
    
    public function validate(): array|bool
    {
        $errors = [];

        if (empty($this->productId) || !is_string($this->productId)) {
            $errors[] = "Invalid or missing product ID.";
        }

        if (empty($this->name) || !is_string($this->name)) {
            $errors[] = "Invalid or missing product name.";
        }

        if (!isset($this->price) || !is_numeric($this->price) || $this->price < 0) {
            $errors[] = "Invalid or missing price.";
        }

        if (!isset($this->quantity) || !is_int($this->quantity) || $this->quantity <= 0) {
            $errors[] = "Invalid or missing quantity.";
        }

        if (empty($this->image) || !filter_var($this->image, FILTER_VALIDATE_URL)) {
            $errors[] = "Invalid or missing image URL.";
        }

        if (!is_array($this->selectedAttributes)) {
            $errors[] = "Selected attributes must be an array.";
        }

        return empty($errors) ? true : $errors;
    }
}
