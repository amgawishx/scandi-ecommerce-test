<?php

declare(strict_types= 1);

namespace MvpMarket\Utility;

use MvpMarket\Database\QueryBuilder;


interface Record
{
    public function getOne($id);
    public function getAll();
    public static function addToQuery(QueryBuilder $query);
}