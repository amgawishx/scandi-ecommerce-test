<?php
namespace MvpMarket\Models;
use MvpMarket\Database\Query;

class CategoryModel
{
    public static function getAll()
    {
        $query = new Query();
        return $query->getCategories();
    }

}