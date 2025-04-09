<?php

namespace MvpMarket\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class Gallery extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Gallery',
            'fields' => [
                'id' => Type::string(),
                'imageUrl' => Type::string(),
            ],
        ]);
    }
}
