<?php

namespace MvpMarket\GraphQL\Types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class Category extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Category',
            'fields' => [
                'id' => Type::string(),
                'name' => Type::string(),
            ],
        ]);
    }
}
