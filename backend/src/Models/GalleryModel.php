<?php

namespace MvpMarket\Models;

use MvpMarket\Models\DataModel;
use MvpMarket\Database\QueryBuilder;

class GalleryModel extends DataModel
{
    protected $galleryId;
    protected $imageUrl;
    const SELECTS = ["g.id AS gallery_id", "g.image_url"];
    public function __construct(array $data = [])
    {
        parent::__construct(data: $data);
        $this->tableName = 'galleries';
        $this->queryBuilder->setFrom($this->tableName);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->galleryId,
            'imageUrl' => $this->imageUrl,
        ];
    }

    public static function addToQuery(QueryBuilder $qb)
    {
        $qb->addJoin("LEFT JOIN galleries g ON g.product_id = p.id");
        foreach (self::SELECTS as $select) {
            $qb->addSelect($select);
        }
    }
}