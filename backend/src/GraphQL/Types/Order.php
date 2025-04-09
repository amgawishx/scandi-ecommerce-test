<?php

namespace MvpMarket\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class Order extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Order',
            'fields' => [
                'id' => Type::int(),
                'product_id' => Type::string(),
                'quantity' => Type::int(),
                'price' => Type::float(),
                'image' => Type::string(),
                'name' => Type::string(),
                'created_at' => Type::string(),
                'attributes' => Type::listOf(TypesRegistry::orderAttributeValue()),
            ],
        ]);
    }
}
