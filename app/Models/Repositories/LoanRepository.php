<?php

namespace App\Models\Repositories;

use App\Models\Entities\Loan;

/**
 * Repositório para gerenciar empréstimos no banco de dados
 */
class LoanRepository extends BaseRepository
{
    protected string $table = 'loans';

    /**
     * Busca um empréstimo por ID e retorna como objeto Loan
     */
    public function findLoanById(int $id): ?Loan
    {
        $data = $this->findById($id);
        return $data ? Loan::fromArray($data) : null;
    }

    /**
     * Retorna todos os empréstimos como objetos Loan
     */
    public function getAllLoans(): array
    {
        $data = $this->findAll();
        return array_map(fn($row) => Loan::fromArray($row), $data);
    }

    /**
     * Busca empréstimos por membro
     */
    public function findByMember(int $memberId): array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE member_id = :member_id
                ORDER BY data_emprestimo DESC";

        $data = $this->db->query($sql, ['member_id' => $memberId]);
        return array_map(fn($row) => Loan::fromArray($row), $data);
    }

    /**
     * Busca empréstimos por livro
     */
    public function findByBook(int $bookId): array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE book_id = :book_id
                ORDER BY data_emprestimo DESC";

        $data = $this->db->query($sql, ['book_id' => $bookId]);
        return array_map(fn($row) => Loan::fromArray($row), $data);
    }

    /**
     * Busca empréstimos ativos
     */
    public function findAtivos(): array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE status = 'ativo'
                ORDER BY data_emprestimo DESC";

        $data = $this->db->query($sql);
        return array_map(fn($row) => Loan::fromArray($row), $data);
    }

    /**
     * Busca empréstimos atrasados
     */
    public function findAtrasados(): array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE status = 'ativo'
                AND data_prevista_devolucao < CURDATE()
                ORDER BY data_prevista_devolucao ASC";

        $data = $this->db->query($sql);
        return array_map(fn($row) => Loan::fromArray($row), $data);
    }

    /**
     * Busca empréstimos com join de membros e livros
     */
    public function findAllWithDetails(): array
    {
        $sql = "SELECT l.*, m.nome as member_name, b.titulo as book_title
                FROM {$this->table} l
                INNER JOIN members m ON l.member_id = m.id
                INNER JOIN books b ON l.book_id = b.id
                ORDER BY l.data_emprestimo DESC";

        return $this->db->query($sql);
    }

    /**
     * Cria um novo empréstimo
     */
    public function create(Loan $loan): int
    {
        $sql = "INSERT INTO {$this->table}
                (member_id, book_id, data_emprestimo, data_prevista_devolucao,
                 data_devolucao, status, observacoes, usuario_responsavel)
                VALUES (:member_id, :book_id, :data_emprestimo, :data_prevista_devolucao,
                        :data_devolucao, :status, :observacoes, :usuario_responsavel)";

        $params = [
            'member_id' => $loan->getMemberId(),
            'book_id' => $loan->getBookId(),
            'data_emprestimo' => $loan->getDataEmprestimo()->format('Y-m-d'),
            'data_prevista_devolucao' => $loan->getDataPrevistaDevolucao()->format('Y-m-d'),
            'data_devolucao' => $loan->getDataDevolucao()?->format('Y-m-d'),
            'status' => $loan->getStatus(),
            'observacoes' => $loan->getObservacoes(),
            'usuario_responsavel' => $loan->getUsuarioResponsavel()
        ];

        return $this->db->insert($sql, $params);
    }

    /**
     * Atualiza um empréstimo existente
     */
    public function update(Loan $loan): bool
    {
        $sql = "UPDATE {$this->table}
                SET member_id = :member_id,
                    book_id = :book_id,
                    data_emprestimo = :data_emprestimo,
                    data_prevista_devolucao = :data_prevista_devolucao,
                    data_devolucao = :data_devolucao,
                    status = :status,
                    observacoes = :observacoes,
                    usuario_responsavel = :usuario_responsavel
                WHERE id = :id";

        $params = [
            'id' => $loan->getId(),
            'member_id' => $loan->getMemberId(),
            'book_id' => $loan->getBookId(),
            'data_emprestimo' => $loan->getDataEmprestimo()->format('Y-m-d'),
            'data_prevista_devolucao' => $loan->getDataPrevistaDevolucao()->format('Y-m-d'),
            'data_devolucao' => $loan->getDataDevolucao()?->format('Y-m-d'),
            'status' => $loan->getStatus(),
            'observacoes' => $loan->getObservacoes(),
            'usuario_responsavel' => $loan->getUsuarioResponsavel()
        ];

        return $this->db->update($sql, $params) > 0;
    }

    /**
     * Registra a devolução de um empréstimo
     */
    public function registrarDevolucao(int $loanId, string $dataDevolucao): bool
    {
        $sql = "UPDATE {$this->table}
                SET data_devolucao = :data_devolucao,
                    status = 'devolvido'
                WHERE id = :id";

        $params = [
            'id' => $loanId,
            'data_devolucao' => $dataDevolucao
        ];

        return $this->db->update($sql, $params) > 0;
    }

    /**
     * Conta empréstimos ativos de um membro
     */
    public function countEmprestimosAtivos(int $memberId): int
    {
        $sql = "SELECT COUNT(*) as count
                FROM {$this->table}
                WHERE member_id = :member_id AND status = 'ativo'";

        $result = $this->db->queryOne($sql, ['member_id' => $memberId]);
        return (int)($result['count'] ?? 0);
    }

    /**
     * Verifica se um livro está emprestado
     */
    public function isLivroEmprestado(int $bookId): bool
    {
        $sql = "SELECT COUNT(*) as count
                FROM {$this->table}
                WHERE book_id = :book_id AND status = 'ativo'";

        $result = $this->db->queryOne($sql, ['book_id' => $bookId]);
        return $result && $result['count'] > 0;
    }
}
