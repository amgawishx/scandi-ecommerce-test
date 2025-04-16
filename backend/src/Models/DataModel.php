<?php

namespace MvpMarket\Models;

use MvpMarket\Database\QueryBuilder;
use MvpMarket\Utility\Record;
use ArrayAccess;
use JsonSerializable;

abstract class DataModel implements ArrayAccess, JsonSerializable, Record
{
    public string $tableName;
    public string $id;
    protected QueryBuilder $queryBuilder;
    public function __construct(array $data = [])
    {
        $this->queryBuilder = new QueryBuilder();
        foreach ($data as $key => $value) {
            $camelKey = self::snakeToCamel($key);
            if (property_exists($this, $camelKey)) {
                $this->$camelKey = $value;
            }
        }
    }

    public function getAll(): array
    {
        $this->queryBuilder->addSelect(field: '*');
        $this->queryBuilder->toSQL();
        return array_map(fn($row) => new static($row), $this->queryBuilder->runSQL() ?? []);
    }

    public function getOne($id)
    {
        throw new \BadMethodCallException("Method not implemented.");
    }


    public function offsetExists(mixed $offset): bool
    {
        return property_exists($this, $offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->$offset ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (property_exists($this, $offset)) {
            $this->$offset = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        if (property_exists($this, $offset)) {
            $this->$offset = null;
        }
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public static function addToQuery(QueryBuilder $query)
    {
        throw new \BadMethodCallException(message: "Method not implemented.");
    }

    private static function snakeToCamel(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }


    abstract public function toArray(): array;

}