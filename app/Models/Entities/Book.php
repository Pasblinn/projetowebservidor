<?php

namespace App\Models\Entities;

use DateTime;

/**
 * Entidade Book - Representa um livro da biblioteca
 */
class Book
{
    private ?int $id = null;
    private string $titulo;
    private string $autor;
    private string $isbn;
    private string $editora;
    private int $anoPublicacao;
    private string $categoria;
    private int $quantidadeTotal;
    private int $quantidadeDisponivel;
    private string $localizacao;
    private ?DateTime $dataCadastro = null;
    private ?DateTime $dataAtualizacao = null;

    public function __construct(
        string $titulo,
        string $autor,
        string $isbn,
        string $editora,
        int $anoPublicacao,
        string $categoria,
        int $quantidadeTotal,
        int $quantidadeDisponivel,
        string $localizacao
    ) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->isbn = $isbn;
        $this->editora = $editora;
        $this->anoPublicacao = $anoPublicacao;
        $this->categoria = $categoria;
        $this->quantidadeTotal = $quantidadeTotal;
        $this->quantidadeDisponivel = $quantidadeDisponivel;
        $this->localizacao = $localizacao;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getAutor(): string
    {
        return $this->autor;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getEditora(): string
    {
        return $this->editora;
    }

    public function getAnoPublicacao(): int
    {
        return $this->anoPublicacao;
    }

    public function getCategoria(): string
    {
        return $this->categoria;
    }

    public function getQuantidadeTotal(): int
    {
        return $this->quantidadeTotal;
    }

    public function getQuantidadeDisponivel(): int
    {
        return $this->quantidadeDisponivel;
    }

    public function getLocalizacao(): string
    {
        return $this->localizacao;
    }

    public function getDataCadastro(): ?DateTime
    {
        return $this->dataCadastro;
    }

    public function getDataAtualizacao(): ?DateTime
    {
        return $this->dataAtualizacao;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function setAutor(string $autor): void
    {
        $this->autor = $autor;
    }

    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function setEditora(string $editora): void
    {
        $this->editora = $editora;
    }

    public function setAnoPublicacao(int $anoPublicacao): void
    {
        $this->anoPublicacao = $anoPublicacao;
    }

    public function setCategoria(string $categoria): void
    {
        $this->categoria = $categoria;
    }

    public function setQuantidadeTotal(int $quantidadeTotal): void
    {
        $this->quantidadeTotal = $quantidadeTotal;
    }

    public function setQuantidadeDisponivel(int $quantidadeDisponivel): void
    {
        $this->quantidadeDisponivel = $quantidadeDisponivel;
    }

    public function setLocalizacao(string $localizacao): void
    {
        $this->localizacao = $localizacao;
    }

    public function setDataCadastro(DateTime $dataCadastro): void
    {
        $this->dataCadastro = $dataCadastro;
    }

    public function setDataAtualizacao(DateTime $dataAtualizacao): void
    {
        $this->dataAtualizacao = $dataAtualizacao;
    }

    /**
     * Verifica se o livro está disponível para empréstimo
     */
    public function isDisponivel(): bool
    {
        return $this->quantidadeDisponivel > 0;
    }

    /**
     * Cria uma instância de Book a partir de um array de dados
     */
    public static function fromArray(array $data): self
    {
        $book = new self(
            $data['titulo'],
            $data['autor'],
            $data['isbn'],
            $data['editora'],
            (int)$data['ano_publicacao'],
            $data['categoria'],
            (int)$data['quantidade_total'],
            (int)$data['quantidade_disponivel'],
            $data['localizacao']
        );

        if (isset($data['id'])) {
            $book->setId((int)$data['id']);
        }

        if (isset($data['data_cadastro'])) {
            $book->setDataCadastro(new DateTime($data['data_cadastro']));
        }

        if (isset($data['data_atualizacao'])) {
            $book->setDataAtualizacao(new DateTime($data['data_atualizacao']));
        }

        return $book;
    }

    /**
     * Converte a entidade para array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'autor' => $this->autor,
            'isbn' => $this->isbn,
            'editora' => $this->editora,
            'ano_publicacao' => $this->anoPublicacao,
            'categoria' => $this->categoria,
            'quantidade_total' => $this->quantidadeTotal,
            'quantidade_disponivel' => $this->quantidadeDisponivel,
            'localizacao' => $this->localizacao,
            'data_cadastro' => $this->dataCadastro?->format('Y-m-d H:i:s'),
            'data_atualizacao' => $this->dataAtualizacao?->format('Y-m-d H:i:s')
        ];
    }
}
