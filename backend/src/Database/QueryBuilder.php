<?php

namespace MvpMarket\Database;

use MvpMarket\Database\Database;
use PDO, PDOException;

class QueryBuilder extends Database
{
    private array $select = [];
    private array $joins = [];
    private string $from = '';
    private array $where = [];
    public string $sql = '';

    public function setFrom(string $tableAlias): void
    {
        $this->from = $tableAlias;
    }

    public function addSelect(string $field): void
    {
        $this->select[] = $field;
    }

    public function addJoin(string $join): void
    {
        $this->joins[] = $join;
    }

    public function addWhere(string $condition): void
    {
        $this->where[] = $condition;
    }

    public function toSQL(): void
    {
        $sql = "SELECT " . implode(", ", $this->select);
        $sql .= " FROM " . $this->from;
        if (!empty($this->joins))
        {
            $sql .= " " . implode(" ", $this->joins);
        }
        if (!empty($this->where))
        {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }
        $this->sql = $sql;
    }
    public function runSQL(): array
    {
        try {
            assert ($this->sql !== "");
            $connection = $this->getConnection();
            $stmt = $connection->query($this->sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
            return [];
        }
    }

    public function clearSQL()
    {
        $this->sql = "";
        $this->select = [];
        $this->joins = [];
        $this->where = [];
    }
}
