<?php

namespace MvpMarket\Models;

class CategoryModel extends DataModel
{
    protected string $name = '';

    public function __construct(array $data = [])
    {
        parent::__construct(data: $data);
        $this->tableName = 'categories';
        $this->queryBuilder->setFrom($this->tableName);
    }
    
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
