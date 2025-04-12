<?php

namespace MvpMarket\Database;

use PDO;
use PDOException;

class QueryBuilder extends Database
{
    private array $select = [];
    private array $joins = [];
    private string $from = '';
    private array $where = [];
    public string $sql = '';
    private array $insertData = [];

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
        if (!empty($this->joins)) {
            $sql .= " " . implode(" ", $this->joins);
        }
        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }
        $this->sql = $sql;
    }

    public function runSQL(): array|bool
    {
        try {
            assert($this->sql !== "");
            $connection = $this->getConnection();
            $queryType = strtoupper(strtok(trim($this->sql), " "));
            $stmt = $connection->prepare($this->sql);
            $success = $stmt->execute();
            if ($queryType === 'SELECT') {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $results;
            } else {
                return $success;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return $queryType === 'SELECT' ? [] : false;
        }
    }

    public function clearSQL(): void
    {
        $this->sql = "";
        $this->select = [];
        $this->joins = [];
        $this->where = [];
        $this->insertData = [];
    }

    public function insertData(array $data): void
    {
        $this->insertData = $data;

        $columns = implode(", ", array_keys($data));

        $values = implode(", ", array_map(function ($value) {
            if (is_array($value)) {
                return "'" . addslashes(json_encode($value)) . "'";
            }

            if (is_string($value)) {
                return "'" . addslashes($value) . "'";
            }

            if (is_null($value)) {
                return "NULL";
            }

            if (is_bool($value)) {
                return $value ? '1' : '0';
            }

            return $value;
        }, array_values($data)));

        $this->sql = "INSERT INTO {$this->from} ({$columns}) VALUES ({$values})";
    }
}
