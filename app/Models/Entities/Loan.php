<?php

namespace App\Models\Entities;

use DateTime;

/**
 * Entidade Loan - Representa um empréstimo de livro
 */
class Loan
{
    private ?int $id = null;
    private int $memberId;
    private int $bookId;
    private DateTime $dataEmprestimo;
    private DateTime $dataPrevistaDevolucao;
    private ?DateTime $dataDevolucao = null;
    private string $status;
    private ?string $observacoes = null;
    private string $usuarioResponsavel;
    private ?DateTime $dataCriacao = null;
    private ?DateTime $dataAtualizacao = null;

    public function __construct(
        int $memberId,
        int $bookId,
        DateTime $dataEmprestimo,
        DateTime $dataPrevistaDevolucao,
        string $usuarioResponsavel,
        string $status = 'ativo',
        ?DateTime $dataDevolucao = null,
        ?string $observacoes = null
    ) {
        $this->memberId = $memberId;
        $this->bookId = $bookId;
        $this->dataEmprestimo = $dataEmprestimo;
        $this->dataPrevistaDevolucao = $dataPrevistaDevolucao;
        $this->usuarioResponsavel = $usuarioResponsavel;
        $this->status = $status;
        $this->dataDevolucao = $dataDevolucao;
        $this->observacoes = $observacoes;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMemberId(): int
    {
        return $this->memberId;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getDataEmprestimo(): DateTime
    {
        return $this->dataEmprestimo;
    }

    public function getDataPrevistaDevolucao(): DateTime
    {
        return $this->dataPrevistaDevolucao;
    }

    public function getDataDevolucao(): ?DateTime
    {
        return $this->dataDevolucao;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getObservacoes(): ?string
    {
        return $this->observacoes;
    }

    public function getUsuarioResponsavel(): string
    {
        return $this->usuarioResponsavel;
    }

    public function getDataCriacao(): ?DateTime
    {
        return $this->dataCriacao;
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

    public function setMemberId(int $memberId): void
    {
        $this->memberId = $memberId;
    }

    public function setBookId(int $bookId): void
    {
        $this->bookId = $bookId;
    }

    public function setDataEmprestimo(DateTime $dataEmprestimo): void
    {
        $this->dataEmprestimo = $dataEmprestimo;
    }

    public function setDataPrevistaDevolucao(DateTime $dataPrevistaDevolucao): void
    {
        $this->dataPrevistaDevolucao = $dataPrevistaDevolucao;
    }

    public function setDataDevolucao(?DateTime $dataDevolucao): void
    {
        $this->dataDevolucao = $dataDevolucao;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setObservacoes(?string $observacoes): void
    {
        $this->observacoes = $observacoes;
    }

    public function setUsuarioResponsavel(string $usuarioResponsavel): void
    {
        $this->usuarioResponsavel = $usuarioResponsavel;
    }

    public function setDataCriacao(DateTime $dataCriacao): void
    {
        $this->dataCriacao = $dataCriacao;
    }

    public function setDataAtualizacao(DateTime $dataAtualizacao): void
    {
        $this->dataAtualizacao = $dataAtualizacao;
    }

    /**
     * Verifica se o empréstimo está atrasado
     */
    public function isAtrasado(): bool
    {
        if ($this->dataDevolucao !== null) {
            return false;
        }

        $hoje = new DateTime();
        return $hoje > $this->dataPrevistaDevolucao;
    }

    /**
     * Cria uma instância de Loan a partir de um array de dados
     */
    public static function fromArray(array $data): self
    {
        $loan = new self(
            (int)$data['member_id'],
            (int)$data['book_id'],
            new DateTime($data['data_emprestimo']),
            new DateTime($data['data_prevista_devolucao']),
            $data['usuario_responsavel'],
            $data['status'] ?? 'ativo',
            isset($data['data_devolucao']) ? new DateTime($data['data_devolucao']) : null,
            $data['observacoes'] ?? null
        );

        if (isset($data['id'])) {
            $loan->setId((int)$data['id']);
        }

        if (isset($data['data_criacao'])) {
            $loan->setDataCriacao(new DateTime($data['data_criacao']));
        }

        if (isset($data['data_atualizacao'])) {
            $loan->setDataAtualizacao(new DateTime($data['data_atualizacao']));
        }

        return $loan;
    }

    /**
     * Converte a entidade para array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'member_id' => $this->memberId,
            'book_id' => $this->bookId,
            'data_emprestimo' => $this->dataEmprestimo->format('Y-m-d'),
            'data_prevista_devolucao' => $this->dataPrevistaDevolucao->format('Y-m-d'),
            'data_devolucao' => $this->dataDevolucao?->format('Y-m-d'),
            'status' => $this->status,
            'observacoes' => $this->observacoes,
            'usuario_responsavel' => $this->usuarioResponsavel,
            'data_criacao' => $this->dataCriacao?->format('Y-m-d H:i:s'),
            'data_atualizacao' => $this->dataAtualizacao?->format('Y-m-d H:i:s')
        ];
    }
}
