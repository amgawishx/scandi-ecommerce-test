<?php

namespace MvpMarket\GraphQL\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class Order extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderItemInput',
            'fields' => [
                'productId' => Type::nonNull(Type::string()),
                'quantity' => Type::nonNull(Type::int()),
                'price' => Type::nonNull(Type::float()),
                'image' => Type::nonNull(Type::string()),
                'name' => Type::nonNull(Type::string()),
                'selectedAttributes' => Type::listOf(
                    new InputObjectType([
                        'name' => 'SelectedAttributeInput',
                        'fields' => [
                            'id'=>Type::nonNull(Type::string()),
                            'name' => Type::nonNull(Type::string()),
                            'value' => Type::nonNull(Type::string())
                        ]
                    ])
                )
            ],
        ]);
    }
}
