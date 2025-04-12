<?php

declare(strict_types=1);

namespace MvpMarket\Models;
use MvpMarket\Database\QueryBuilder;

class AttributeValueModel extends DataModel
{
    protected $attributeId;
    protected $attributeValueId;
    protected $attributeName;
    protected $attributeType;
    protected string $value;
    protected string $displayValue;
    const JOINS = [
        "LEFT JOIN product_attribute_values pav ON pav.product_id = p.id",
        "LEFT JOIN attribute_values av ON pav.attribute_value_id = av.id",
        "LEFT JOIN attributes a ON av.attribute_id = a.id"
    ];
    const SELECTS = [
        "av.id AS attribute_value_id",
        "av.value",
        "av.display_value",
        "a.id AS attribute_id",
        "a.name AS attribute_name",
        "a.type AS attribute_type"
    ];
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->tableName = "attribute_values";
        $this->queryBuilder->setFrom($this->tableName);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->attributeValueId,
            'value' => $this->value,
            'displayValue' => $this->displayValue,
            'attribute' => [
                'id'=> $this->attributeId,
                'name'=> $this->attributeName,
                'type'=> $this->attributeType
            ],
        ];
    }

    public static function addToQuery(QueryBuilder $qb)
    {
        foreach (self::JOINS as $join) {
            $qb->addJoin($join);
        }

        foreach (self::SELECTS as $select) {
            $qb->addSelect($select);
        }
    }

}