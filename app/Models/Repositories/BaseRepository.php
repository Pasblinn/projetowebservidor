<?php

namespace App\Models\Repositories;

use App\Core\Database;
use PDO;

/**
 * Classe abstrata BaseRepository
 * Fornece métodos comuns para todos os repositórios
 */
abstract class BaseRepository
{
    protected Database $db;
    protected PDO $pdo;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getConnection();
    }

    /**
     * Busca todos os registros da tabela
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        return $this->db->query($sql);
    }

    /**
     * Busca um registro por ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        return $this->db->queryOne($sql, ['id' => $id]);
    }

    /**
     * Deleta um registro por ID
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->db->delete($sql, ['id' => $id]) > 0;
    }

    /**
     * Verifica se um registro existe pelo ID
     */
    public function exists(int $id): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE id = :id";
        $result = $this->db->queryOne($sql, ['id' => $id]);
        return $result && $result['count'] > 0;
    }

    /**
     * Conta o total de registros na tabela
     */
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $result = $this->db->queryOne($sql);
        return (int)($result['count'] ?? 0);
    }
}
