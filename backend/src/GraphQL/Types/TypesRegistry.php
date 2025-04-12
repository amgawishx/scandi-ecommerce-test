<?php

namespace MvpMarket\GraphQL\Types;

use MvpMarket\Models\OrderModel;

class TypesRegistry
{
    private static ?Product $product = null;
    private static ?Price $price = null;
    private static ?Gallery $gallery = null;
    private static ?AttributeValue $attributeValue = null;
    private static ?ProductAttributeValueWrapper $attributeWrapper = null;
    private static ?Order $order = null;
    private static ?OrderAttributeValue $orderAttributeValue = null;
    private static ?Category $category = null;
    private static ?Attribute $attribute = null;

    private static ?OrderModel $orderModel = null;

    public static function product(): Product
    {
        return self::$product ??= new Product();
    }

    public static function price(): Price
    {
        return self::$price ??= new Price();
    }

    public static function gallery(): Gallery
    {
        return self::$gallery ??= new Gallery();
    }

    public static function attributeValue(): AttributeValue
    {
        return self::$attributeValue ??= new AttributeValue();
    }

    public static function productAttributeValueWrapper(): ProductAttributeValueWrapper
    {
        return self::$attributeWrapper ??= new ProductAttributeValueWrapper();
    }

    public static function order(): Order
    {
        return self::$order ??= new Order();
    }

    public static function orderAttributeValue(): OrderAttributeValue
    {
        return self::$orderAttributeValue ??= new OrderAttributeValue();
    }

    public static function category(): Category
    {
        return self::$category ??= new Category();
    }

    public static function attribute(): Attribute
    {
        return self::$attribute ??= new Attribute();
    }

    public static function orderModel(): OrderModel
    {
        return self::$orderModel ??= new OrderModel();
    }
}
