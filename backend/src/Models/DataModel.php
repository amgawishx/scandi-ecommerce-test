<?php

namespace MvpMarket\Models;

use MvpMarket\Database\QueryBuilder;
use MvpMarket\Utility\Record;
use ArrayAccess;
use JsonSerializable;
use Throwable;

abstract class DataModel implements ArrayAccess, JsonSerializable, Record
{
    public string $tableName;
    public string $id;
    protected QueryBuilder $queryBuilder;

    public function __construct(array $data = [])
    {
        error_log("[DataModel] Constructing " . static::class);

        try {
            $this->queryBuilder = new QueryBuilder();

            foreach ($data as $key => $value) {
                $camelKey = self::snakeToCamel($key);
                if (property_exists($this, $camelKey)) {
                    $this->$camelKey = $value;
                    error_log("[DataModel] Mapped $key → \$$camelKey = " . var_export($value, true));
                } else {
                    error_log("[DataModel] Skipped unmapped property: $key");
                }
            }
        } catch (Throwable $e) {
            error_log("[DataModel ERROR] Failed to construct " . static::class . ": " . $e->getMessage());
            throw $e;
        }
    }

    public function getAll(): array
    {
        error_log("[DataModel] getAll() called on " . static::class);

        try {
            $this->queryBuilder->addSelect(field: '*');
            $this->queryBuilder->toSQL();

            error_log("[DataModel] Final SQL: ".$this->queryBuilder->sql);

            $results = $this->queryBuilder->runSQL();
            $count = is_array($results) ? count($results) : 0;
            error_log("[DataModel] Rows fetched: $count");

            return array_map(fn($row) => new static($row), $results ?? []);
        } catch (Throwable $e) {
            error_log("[DataModel ERROR] getAll failed: " . $e->getMessage());
            return [];
        }
    }

    public function getOne($id)
    {
        throw new \BadMethodCallException("Method getOne() not implemented in " . static::class);
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
        throw new \BadMethodCallException("Method addToQuery() not implemented in " . static::class);
    }

    private static function snakeToCamel(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }

    abstract public function toArray(): array;
}
