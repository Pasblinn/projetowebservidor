<?php

namespace App\Models\Repositories;

use App\Models\Entities\Member;

/**
 * Repositório para gerenciar membros da biblioteca no banco de dados
 */
class MemberRepository extends BaseRepository
{
    protected string $table = 'members';

    /**
     * Busca um membro por CPF
     */
    public function findByCpf(string $cpf): ?Member
    {
        $sql = "SELECT * FROM {$this->table} WHERE cpf = :cpf LIMIT 1";
        $data = $this->db->queryOne($sql, ['cpf' => $cpf]);

        return $data ? Member::fromArray($data) : null;
    }

    /**
     * Busca um membro por email
     */
    public function findByEmail(string $email): ?Member
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $data = $this->db->queryOne($sql, ['email' => $email]);

        return $data ? Member::fromArray($data) : null;
    }

    /**
     * Busca um membro por ID e retorna como objeto Member
     */
    public function findMemberById(int $id): ?Member
    {
        $data = $this->findById($id);
        return $data ? Member::fromArray($data) : null;
    }

    /**
     * Retorna todos os membros como objetos Member
     */
    public function getAllMembers(): array
    {
        $data = $this->findAll();
        return array_map(fn($row) => Member::fromArray($row), $data);
    }

    /**
     * Busca membros por categoria
     */
    public function findByCategoria(string $categoria): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE categoria = :categoria ORDER BY nome";
        $data = $this->db->query($sql, ['categoria' => $categoria]);

        return array_map(fn($row) => Member::fromArray($row), $data);
    }

    /**
     * Busca membros ativos
     */
    public function findAtivos(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE ativo = 1 ORDER BY nome";
        $data = $this->db->query($sql);

        return array_map(fn($row) => Member::fromArray($row), $data);
    }

    /**
     * Busca membros por termo (nome ou email)
     */
    public function search(string $term): array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE nome LIKE :term OR email LIKE :term
                ORDER BY nome";

        $data = $this->db->query($sql, ['term' => "%{$term}%"]);

        return array_map(fn($row) => Member::fromArray($row), $data);
    }

    /**
     * Cria um novo membro
     */
    public function create(Member $member): int
    {
        $sql = "INSERT INTO {$this->table}
                (nome, email, telefone, endereco, cpf, data_nascimento, categoria, ativo)
                VALUES (:nome, :email, :telefone, :endereco, :cpf, :data_nascimento, :categoria, :ativo)";

        $params = [
            'nome' => $member->getNome(),
            'email' => $member->getEmail(),
            'telefone' => $member->getTelefone(),
            'endereco' => $member->getEndereco(),
            'cpf' => $member->getCpf(),
            'data_nascimento' => $member->getDataNascimento()->format('Y-m-d'),
            'categoria' => $member->getCategoria(),
            'ativo' => $member->isAtivo() ? 1 : 0
        ];

        return $this->db->insert($sql, $params);
    }

    /**
     * Atualiza um membro existente
     */
    public function update(Member $member): bool
    {
        $sql = "UPDATE {$this->table}
                SET nome = :nome,
                    email = :email,
                    telefone = :telefone,
                    endereco = :endereco,
                    cpf = :cpf,
                    data_nascimento = :data_nascimento,
                    categoria = :categoria,
                    ativo = :ativo
                WHERE id = :id";

        $params = [
            'id' => $member->getId(),
            'nome' => $member->getNome(),
            'email' => $member->getEmail(),
            'telefone' => $member->getTelefone(),
            'endereco' => $member->getEndereco(),
            'cpf' => $member->getCpf(),
            'data_nascimento' => $member->getDataNascimento()->format('Y-m-d'),
            'categoria' => $member->getCategoria(),
            'ativo' => $member->isAtivo() ? 1 : 0
        ];

        return $this->db->update($sql, $params) > 0;
    }

    /**
     * Verifica se um CPF já existe
     */
    public function cpfExists(string $cpf, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE cpf = :cpf";
        $params = ['cpf' => $cpf];

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
