<?php

declare(strict_types= 1);

namespace MvpMarket\Models;

use MvpMarket\utility\Validator;
use InvalidArgumentException;
use MvpMarket\Database\Query;

class OrderModel implements Validator
{
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public static function placeOrder(array $items): bool
    {
        (new self($items))->validate();

        $query = new Query();
        return $query->placeOrder($items);
    }

    public function validate(): void
    {
        if (empty($this->items)) {
            throw new InvalidArgumentException("Order must contain at least one item.");
        }

        foreach ($this->items as $index => $item) {
            if (!isset($item['productId']) || !is_string($item['productId'])) {
                throw new InvalidArgumentException("Item #$index: 'productId' is required and must be a string.");
            }

            if (!isset($item['quantity']) || !is_int($item['quantity']) || $item['quantity'] <= 0) {
                throw new InvalidArgumentException("Item #$index: 'quantity' must be a positive integer.");
            }

            if (isset($item['selectedAttributes']) && !is_array($item['selectedAttributes'])) {
                throw new InvalidArgumentException("Item #$index: 'selectedAttributes' must be an array if provided.");
            }
        }
    }
}
