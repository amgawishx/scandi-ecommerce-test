<?php

namespace MvpMarket\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;

class OrderAttributeValue extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderAttributeValue',
            'fields' => [
                'attributeValue' => TypesRegistry::attributeValue(),
            ],
        ]);
    }
}
