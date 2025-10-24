<?php

namespace App\Models\Repositories;

use App\Models\Entities\Book;

/**
 * Repositório para gerenciar livros no banco de dados
 */
class BookRepository extends BaseRepository
{
    protected string $table = 'books';

    /**
     * Busca um livro por ISBN
     */
    public function findByIsbn(string $isbn): ?Book
    {
        $sql = "SELECT * FROM {$this->table} WHERE isbn = :isbn LIMIT 1";
        $data = $this->db->queryOne($sql, ['isbn' => $isbn]);

        return $data ? Book::fromArray($data) : null;
    }

    /**
     * Busca um livro por ID e retorna como objeto Book
     */
    public function findBookById(int $id): ?Book
    {
        $data = $this->findById($id);
        return $data ? Book::fromArray($data) : null;
    }

    /**
     * Retorna todos os livros como objetos Book
     */
    public function getAllBooks(): array
    {
        $data = $this->findAll();
        return array_map(fn($row) => Book::fromArray($row), $data);
    }

    /**
     * Busca livros por categoria
     */
    public function findByCategoria(string $categoria): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE categoria = :categoria ORDER BY titulo";
        $data = $this->db->query($sql, ['categoria' => $categoria]);

        return array_map(fn($row) => Book::fromArray($row), $data);
    }

    /**
     * Busca livros por termo (título ou autor)
     */
    public function search(string $term): array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE titulo LIKE :term OR autor LIKE :term
                ORDER BY titulo";

        $data = $this->db->query($sql, ['term' => "%{$term}%"]);

        return array_map(fn($row) => Book::fromArray($row), $data);
    }

    /**
     * Cria um novo livro
     */
    public function create(Book $book): int
    {
        $sql = "INSERT INTO {$this->table}
                (titulo, autor, isbn, editora, ano_publicacao, categoria,
                 quantidade_total, quantidade_disponivel, localizacao)
                VALUES (:titulo, :autor, :isbn, :editora, :ano_publicacao, :categoria,
                        :quantidade_total, :quantidade_disponivel, :localizacao)";

        $params = [
            'titulo' => $book->getTitulo(),
            'autor' => $book->getAutor(),
            'isbn' => $book->getIsbn(),
            'editora' => $book->getEditora(),
            'ano_publicacao' => $book->getAnoPublicacao(),
            'categoria' => $book->getCategoria(),
            'quantidade_total' => $book->getQuantidadeTotal(),
            'quantidade_disponivel' => $book->getQuantidadeDisponivel(),
            'localizacao' => $book->getLocalizacao()
        ];

        return $this->db->insert($sql, $params);
    }

    /**
     * Atualiza um livro existente
     */
    public function update(Book $book): bool
    {
        $sql = "UPDATE {$this->table}
                SET titulo = :titulo,
                    autor = :autor,
                    isbn = :isbn,
                    editora = :editora,
                    ano_publicacao = :ano_publicacao,
                    categoria = :categoria,
                    quantidade_total = :quantidade_total,
                    quantidade_disponivel = :quantidade_disponivel,
                    localizacao = :localizacao
                WHERE id = :id";

        $params = [
            'id' => $book->getId(),
            'titulo' => $book->getTitulo(),
            'autor' => $book->getAutor(),
            'isbn' => $book->getIsbn(),
            'editora' => $book->getEditora(),
            'ano_publicacao' => $book->getAnoPublicacao(),
            'categoria' => $book->getCategoria(),
            'quantidade_total' => $book->getQuantidadeTotal(),
            'quantidade_disponivel' => $book->getQuantidadeDisponivel(),
            'localizacao' => $book->getLocalizacao()
        ];

        return $this->db->update($sql, $params) > 0;
    }

    /**
     * Atualiza a quantidade disponível de um livro
     */
    public function updateQuantidadeDisponivel(int $bookId, int $quantidade): bool
    {
        $sql = "UPDATE {$this->table}
                SET quantidade_disponivel = :quantidade
                WHERE id = :id";

        $params = [
            'id' => $bookId,
            'quantidade' => $quantidade
        ];

        return $this->db->update($sql, $params) > 0;
    }

    /**
     * Incrementa a quantidade disponível (devolução)
     */
    public function incrementarDisponivel(int $bookId): bool
    {
        $sql = "UPDATE {$this->table}
                SET quantidade_disponivel = quantidade_disponivel + 1
                WHERE id = :id";

        return $this->db->update($sql, ['id' => $bookId]) > 0;
    }

    /**
     * Decrementa a quantidade disponível (empréstimo)
     */
    public function decrementarDisponivel(int $bookId): bool
    {
        $sql = "UPDATE {$this->table}
                SET quantidade_disponivel = quantidade_disponivel - 1
                WHERE id = :id AND quantidade_disponivel > 0";

        return $this->db->update($sql, ['id' => $bookId]) > 0;
    }

    /**
     * Verifica se um ISBN já existe
     */
    public function isbnExists(string $isbn, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE isbn = :isbn";
        $params = ['isbn' => $isbn];

        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $result = $this->db->queryOne($sql, $params);
        return $result && $result['count'] > 0;
    }

    /**
     * Retorna livros disponíveis para empréstimo
     */
    public function findDisponiveis(): array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE quantidade_disponivel > 0
                ORDER BY titulo";

        $data = $this->db->query($sql);
        return array_map(fn($row) => Book::fromArray($row), $data);
    }
}
