<?php

namespace MvpMarket\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class Price extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Price',
            'fields' => [
                'id' => Type::nonNull(Type::string()),
                'amount' => Type::float(),
                'currencyLabel' => Type::string(),
                'currencySymbol' => Type::string(),
            ],
        ]);
    }
}
