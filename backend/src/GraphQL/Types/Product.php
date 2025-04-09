<?php
namespace MvpMarket\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;


class Product extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => fn() => [
                'id' => Type::nonNull(Type::string()),
                'name' => Type::string(),
                'inStock' => Type::boolean(),
                'description' => Type::string(),
                'category' => Type::string(),
                'brand' => Type::string(),
                'prices' => Type::listOf(TypesRegistry::price()),
                'galleries' => Type::listOf(TypesRegistry::gallery()),
                'attributes' => Type::listOf(TypesRegistry::productAttributeValueWrapper()),
            ],
        ]);
    }
}
