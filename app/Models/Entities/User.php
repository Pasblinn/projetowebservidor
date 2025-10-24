<?php

namespace App\Models\Entities;

use DateTime;

/**
 * Entidade User - Representa um usuário do sistema
 */
class User
{
    private ?int $id = null;
    private string $username;
    private string $password;
    private string $email;
    private string $nome;
    private string $tipo;
    private bool $ativo;
    private ?DateTime $dataCriacao = null;
    private ?DateTime $dataAtualizacao = null;

    public function __construct(
        string $username,
        string $password,
        string $email,
        string $nome,
        string $tipo = 'bibliotecario',
        bool $ativo = true
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->nome = $nome;
        $this->tipo = $tipo;
        $this->ativo = $ativo;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
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

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function setAtivo(bool $ativo): void
    {
        $this->ativo = $ativo;
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
     * Cria uma instância de User a partir de um array de dados
     */
    public static function fromArray(array $data): self
    {
        $user = new self(
            $data['username'],
            $data['password'],
            $data['email'],
            $data['nome'],
            $data['tipo'] ?? 'bibliotecario',
            (bool)($data['ativo'] ?? true)
        );

        if (isset($data['id'])) {
            $user->setId((int)$data['id']);
        }

        if (isset($data['data_criacao'])) {
            $user->setDataCriacao(new DateTime($data['data_criacao']));
        }

        if (isset($data['data_atualizacao'])) {
            $user->setDataAtualizacao(new DateTime($data['data_atualizacao']));
        }

        return $user;
    }

    /**
     * Converte a entidade para array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password,
            'email' => $this->email,
            'nome' => $this->nome,
            'tipo' => $this->tipo,
            'ativo' => $this->ativo,
            'data_criacao' => $this->dataCriacao?->format('Y-m-d H:i:s'),
            'data_atualizacao' => $this->dataAtualizacao?->format('Y-m-d H:i:s')
        ];
    }
}
