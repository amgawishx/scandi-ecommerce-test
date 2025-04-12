<?php

namespace MvpMarket\Models;


use MvpMarket\Models\DataModel;
use MvpMarket\Database\QueryBuilder;

class PriceModel extends DataModel
{
    protected $priceId;
    protected $amount;
    protected string $currencyLabel; 
    protected string $currencySymbol;
    const SELECTS = ["pr.id AS price_id", "pr.amount", "pr.currency_label", "pr.currency_symbol"];
    public function __construct(array $data = [])
    {
        parent::__construct(data: $data);
        $this->tableName = 'prices';
        $this->queryBuilder->setFrom($this->tableName);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->priceId,
            'amount'=> (float) $this->amount,
            'currencyLabel'=> $this->currencyLabel,
            'currencySymbol'=> $this->currencySymbol,
        ];
    }

    public static function addToQuery(QueryBuilder $qb)
    {
        $qb->addJoin("LEFT JOIN prices pr ON pr.product_id = p.id");
        foreach (self::SELECTS as $select) {
            $qb->addSelect($select);
        }
    }

}