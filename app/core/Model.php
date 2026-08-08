<?php

declare(strict_types=1);

namespace App\Core;

use App\Config\Database;
use PDO;
use PDOStatement;

abstract class Model
{
    protected PDO $db;

    public function __construct(?PDO $pdo = null)
    {
        $this->db = $pdo ?? Database::getInstance()->getConnection();
    }

    /**
     * @param array<string, mixed> $params
     * @return array<int, array<string, mixed>>
     */
    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->prepare($sql, $params)->fetchAll();
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>|false
     */
    protected function fetchOne(string $sql, array $params = []): array|false
    {
        return $this->prepare($sql, $params)->fetch();
    }

    /**
     * @param array<string, mixed> $params
     */
    protected function prepare(string $sql, array $params = []): PDOStatement
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function insert(string $table, array $data): int
    {
        $fields = array_keys($data);
        $placeholders = array_map(static fn (string $field): string => ':' . $field, $fields);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', $fields),
            implode(', ', $placeholders)
        );

        $this->prepare($sql, $data);

        return (int) $this->db->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $whereParams
     */
    protected function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $assignments = [];

        foreach (array_keys($data) as $field) {
            $assignments[] = $field . ' = :' . $field;
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            implode(', ', $assignments),
            $where
        );

        return $this->prepare($sql, array_merge($data, $whereParams))->rowCount();
    }

    /**
     * @param array<string, mixed> $whereParams
     */
    protected function delete(string $table, string $where, array $whereParams = []): int
    {
        $sql = sprintf('DELETE FROM %s WHERE %s', $table, $where);

        return $this->prepare($sql, $whereParams)->rowCount();
    }
}
