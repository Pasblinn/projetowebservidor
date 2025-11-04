<?php

namespace App\Models\Repositories;

use App\Models\Entities\User;

/**
 * Repositório para gerenciar usuários no banco de dados
 */
class UserRepository extends BaseRepository
{
    protected string $table = 'users';

    /**
     * Busca um usuário por username
     */
    public function findByUsername(string $username): ?User
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        $data = $this->db->queryOne($sql, ['username' => $username]);

        return $data ? User::fromArray($data) : null;
    }

    /**
     * Busca um usuário por email
     */
    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $data = $this->db->queryOne($sql, ['email' => $email]);

        return $data ? User::fromArray($data) : null;
    }

    /**
     * Busca um usuário por ID e retorna como objeto User
     */
    public function findUserById(int $id): ?User
    {
        $data = $this->findById($id);
        return $data ? User::fromArray($data) : null;
    }

    /**
     * Retorna todos os usuários como objetos User
     */
    public function getAllUsers(): array
    {
        $data = $this->findAll();
        return array_map(fn($row) => User::fromArray($row), $data);
    }

    /**
     * Cria um novo usuário
     */
    public function create(User $user): int
    {
        $sql = "INSERT INTO {$this->table}
                (username, password, email, nome, tipo, ativo)
                VALUES (:username, :password, :email, :nome, :tipo, :ativo)";

        $params = [
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'nome' => $user->getNome(),
            'tipo' => $user->getTipo(),
            'ativo' => $user->isAtivo() ? 1 : 0
        ];

        return $this->db->insert($sql, $params);
    }

    /**
     * Atualiza um usuário existente
     */
    public function update(User $user): bool
    {
        $sql = "UPDATE {$this->table}
                SET username = :username,
                    email = :email,
                    nome = :nome,
                    tipo = :tipo,
                    ativo = :ativo
                WHERE id = :id";

        $params = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'nome' => $user->getNome(),
            'tipo' => $user->getTipo(),
            'ativo' => $user->isAtivo() ? 1 : 0
        ];

        return $this->db->update($sql, $params) > 0;
    }

    /**
     * Atualiza a senha de um usuário
     */
    public function updatePassword(int $userId, string $hashedPassword): bool
    {
        $sql = "UPDATE {$this->table} SET password = :password WHERE id = :id";

        $params = [
            'id' => $userId,
            'password' => $hashedPassword
        ];

        return $this->db->update($sql, $params) > 0;
    }

    /**
     * Verifica se um username já existe
     */
    public function usernameExists(string $username, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = :username";
        $params = ['username' => $username];

        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $result = $this->db->queryOne($sql, $params);
        return $result && $result['count'] > 0;
    }

    /**
     * Verifica se um email já existe
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $result = $this->db->queryOne($sql, $params);
        return $result && $result['count'] > 0;
    }
}
