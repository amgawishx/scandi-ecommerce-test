<?php
namespace MvpMarket\Models;
use MvpMarket\Database\Query;
use Illuminate\Support\Arr;

class ProductModel
{
    public static function getAll()
    {
        $query = new Query();
        $rows = $query->getAllProducts();
    
        $products = [];
    
        foreach ($rows as $row) {
            $productId = $row['product_id'];
    
            if (!isset($products[$productId])) {
                $products[$productId] = [
                    'id' => $row['product_id'],
                    'name' => $row['name'],
                    'inStock' => (bool) $row['in_stock'],
                    'description' => $row['description'],
                    'category' => $row['category'],
                    'brand' => $row['brand'],
                    'prices' => [],
                    'galleries' => [],
                    'attributes' => [],
                ];
            }
    
            $priceId = $row['price_id'];
            if ($priceId && !self::inArrayById($products[$productId]['prices'], $priceId)) {
                $products[$productId]['prices'][] = [
                    'id' => $priceId,
                    'amount' => $row['amount'],
                    'currencyLabel' => $row['currency_label'],
                    'currencySymbol' => $row['currency_symbol'],
                ];
            }
    
            $galleryId = $row['gallery_id'];
            if ($galleryId && !self::inArrayById($products[$productId]['galleries'], $galleryId)) {
                $products[$productId]['galleries'][] = [
                    'id' => $galleryId,
                    'imageUrl' => $row['image_url'],
                ];
            }
    
            $attributeValueId = $row['attribute_value_id'];
            if ($attributeValueId && !self::inArrayById($products[$productId]['attributes'], $attributeValueId, 'attributeValue')) {
                $products[$productId]['attributes'][] = [
                    'attributeValue' => [
                        'id' => $attributeValueId,
                        'value' => $row['value'],
                        'displayValue' => $row['display_value'],
                        'attribute' => [
                            'id' => $row['attribute_id'],
                            'name' => $row['attribute_name'],
                            'type' => $row['attribute_type'],
                        ],
                    ],
                ];
            }
        }
    
        return array_values($products);
    }
    
    private static function inArrayById(array $array, $id, $nestedKey = null): bool
    {
        foreach ($array as $item) {
            if ($nestedKey) {
                if (isset($item[$nestedKey]['id']) && $item[$nestedKey]['id'] === $id) {
                    return true;
                }
            } else {
                if (isset($item['id']) && $item['id'] === $id) {
                    return true;
                }
            }
        }
        return false;
    }
    
    public static function getOne($id)
    {
        $query = new Query();
        return $query->getProductByID($id);
    }

}