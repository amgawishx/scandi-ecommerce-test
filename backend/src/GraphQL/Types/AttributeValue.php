<?php

namespace MvpMarket\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeValue extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'AttributeValue',
            'fields' => [
                'id' => Type::string(),
                'value' => Type::string(),
                'displayValue' => Type::string(),
                'attribute' => TypesRegistry::attribute(),
            ],
        ]);
    }
}
