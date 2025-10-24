<?php

namespace App\Models\Entities;

use DateTime;

/**
 * Entidade Member - Representa um membro da biblioteca
 */
class Member
{
    private ?int $id = null;
    private string $nome;
    private string $email;
    private string $telefone;
    private string $endereco;
    private string $cpf;
    private DateTime $dataNascimento;
    private string $categoria;
    private bool $ativo;
    private ?DateTime $dataCadastro = null;
    private ?DateTime $dataAtualizacao = null;

    public function __construct(
        string $nome,
        string $email,
        string $telefone,
        string $endereco,
        string $cpf,
        DateTime $dataNascimento,
        string $categoria,
        bool $ativo = true
    ) {
        $this->nome = $nome;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->endereco = $endereco;
        $this->cpf = $cpf;
        $this->dataNascimento = $dataNascimento;
        $this->categoria = $categoria;
        $this->ativo = $ativo;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTelefone(): string
    {
        return $this->telefone;
    }

    public function getEndereco(): string
    {
        return $this->endereco;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getDataNascimento(): DateTime
    {
        return $this->dataNascimento;
    }

    public function getCategoria(): string
    {
        return $this->categoria;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
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

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setTelefone(string $telefone): void
    {
        $this->telefone = $telefone;
    }

    public function setEndereco(string $endereco): void
    {
        $this->endereco = $endereco;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function setDataNascimento(DateTime $dataNascimento): void
    {
        $this->dataNascimento = $dataNascimento;
    }

    public function setCategoria(string $categoria): void
    {
        $this->categoria = $categoria;
    }

    public function setAtivo(bool $ativo): void
    {
        $this->ativo = $ativo;
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
     * Cria uma instância de Member a partir de um array de dados
     */
    public static function fromArray(array $data): self
    {
        $member = new self(
            $data['nome'],
            $data['email'],
            $data['telefone'],
            $data['endereco'],
            $data['cpf'],
            new DateTime($data['data_nascimento']),
            $data['categoria'],
            (bool)($data['ativo'] ?? true)
        );

        if (isset($data['id'])) {
            $member->setId((int)$data['id']);
        }

        if (isset($data['data_cadastro'])) {
            $member->setDataCadastro(new DateTime($data['data_cadastro']));
        }

        if (isset($data['data_atualizacao'])) {
            $member->setDataAtualizacao(new DateTime($data['data_atualizacao']));
        }

        return $member;
    }

    /**
     * Converte a entidade para array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'endereco' => $this->endereco,
            'cpf' => $this->cpf,
            'data_nascimento' => $this->dataNascimento->format('Y-m-d'),
            'categoria' => $this->categoria,
            'ativo' => $this->ativo,
            'data_cadastro' => $this->dataCadastro?->format('Y-m-d H:i:s'),
            'data_atualizacao' => $this->dataAtualizacao?->format('Y-m-d H:i:s')
        ];
    }
}
