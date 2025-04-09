<?php

namespace MvpMarket\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
class ProductAttributeValueWrapper extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'ProductAttributeValueWrapper',
            'fields' => [
                'attributeValue' => TypesRegistry::attributeValue(),
            ],
        ]);
    }
}
