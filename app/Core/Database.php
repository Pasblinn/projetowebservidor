<?php

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Classe Database - Gerenciamento de conexão com banco de dados via PDO
 * Implementa o padrão Singleton para garantir uma única conexão
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    /**
     * Construtor privado para implementar Singleton
     */
    private function __construct()
    {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $database = $_ENV['DB_DATABASE'] ?? 'biblioteca';
        $username = $_ENV['DB_USERNAME'] ?? 'root';
        $password = $_ENV['DB_PASSWORD'] ?? '';

        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ];

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new PDOException("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Obtém a instância única da classe Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Obtém a conexão PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Executa uma query SELECT e retorna todos os resultados
     */
    public function query(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->execute($sql, $params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new PDOException("Erro ao executar query: " . $e->getMessage());
        }
    }

    /**
     * Executa uma query e retorna apenas a primeira linha
     */
    public function queryOne(string $sql, array $params = []): ?array
    {
        try {
            $stmt = $this->execute($sql, $params);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            throw new PDOException("Erro ao executar query: " . $e->getMessage());
        }
    }

    /**
     * Executa uma query de INSERT, UPDATE ou DELETE
     */
    public function execute(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new PDOException("Erro ao executar comando: " . $e->getMessage());
        }
    }

    /**
     * Insere um registro e retorna o ID inserido
     */
    public function insert(string $sql, array $params = []): int
    {
        $this->execute($sql, $params);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Atualiza registros e retorna o número de linhas afetadas
     */
    public function update(string $sql, array $params = []): int
    {
        $stmt = $this->execute($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Deleta registros e retorna o número de linhas afetadas
     */
    public function delete(string $sql, array $params = []): int
    {
        $stmt = $this->execute($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Inicia uma transação
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit da transação
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback da transação
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * Previne clonagem da instância
     */
    private function __clone() {}

    /**
     * Previne deserialização da instância
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
