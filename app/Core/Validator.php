<?php

namespace App\Core;

use Respect\Validation\Validator as v;

/**
 * Validator - Sistema de validação usando Respect\Validation
 *
 * Fornece validações robustas para:
 * - CPF, Email, ISBN
 * - Dados de formulários
 * - Regras de negócio
 */
class Validator
{
    /**
     * Valida CPF brasileiro
     */
    public static function validateCPF(string $cpf): bool
    {
        try {
            v::cpf()->check($cpf);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Valida Email
     */
    public static function validateEmail(string $email): bool
    {
        try {
            v::email()->check($email);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Valida ISBN (10 ou 13 dígitos)
     */
    public static function validateISBN(string $isbn): bool
    {
        // Remove hífens e espaços
        $isbn = preg_replace('/[\s-]+/', '', $isbn);

        // Validação simples de ISBN (10 ou 13 dígitos alfanuméricos)
        return (strlen($isbn) === 10 || strlen($isbn) === 13) && ctype_alnum($isbn);
    }

    /**
     * Valida telefone brasileiro
     */
    public static function validatePhone(string $phone): bool
    {
        // Remove caracteres não numéricos
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Validação simples: 10 ou 11 dígitos (DDD + número)
        return strlen($phone) >= 10 && strlen($phone) <= 11;
    }

    /**
     * Valida dados de livro
     */
    public static function validateBook(array $data): array
    {
        $errors = [];

        // Título
        if (!isset($data['titulo']) || empty(trim($data['titulo']))) {
            $errors[] = 'Título é obrigatório';
        } elseif (strlen($data['titulo']) > 255) {
            $errors[] = 'Título muito longo (máximo 255 caracteres)';
        }

        // Autor
        if (!isset($data['autor']) || empty(trim($data['autor']))) {
            $errors[] = 'Autor é obrigatório';
        } elseif (strlen($data['autor']) > 255) {
            $errors[] = 'Nome do autor muito longo (máximo 255 caracteres)';
        }

        // ISBN
        if (!isset($data['isbn']) || empty(trim($data['isbn']))) {
            $errors[] = 'ISBN é obrigatório';
        } elseif (!self::validateISBN($data['isbn'])) {
            $errors[] = 'ISBN inválido (deve ter 10 ou 13 dígitos)';
        }

        // Editora
        if (!isset($data['editora']) || empty(trim($data['editora']))) {
            $errors[] = 'Editora é obrigatória';
        }

        // Ano de publicação
        if (!isset($data['ano_publicacao']) || empty($data['ano_publicacao'])) {
            $errors[] = 'Ano de publicação é obrigatório';
        } elseif (!is_numeric($data['ano_publicacao'])) {
            $errors[] = 'Ano de publicação deve ser um número';
        } elseif ($data['ano_publicacao'] < 1000 || $data['ano_publicacao'] > date('Y')) {
            $errors[] = 'Ano de publicação inválido';
        }

        // Categoria
        if (!isset($data['categoria']) || empty(trim($data['categoria']))) {
            $errors[] = 'Categoria é obrigatória';
        }

        // Quantidade
        if (!isset($data['quantidade_total']) || empty($data['quantidade_total'])) {
            $errors[] = 'Quantidade é obrigatória';
        } elseif (!is_numeric($data['quantidade_total']) || $data['quantidade_total'] < 1) {
            $errors[] = 'Quantidade deve ser um número maior que zero';
        }

        // Localização
        if (!isset($data['localizacao']) || empty(trim($data['localizacao']))) {
            $errors[] = 'Localização é obrigatória';
        }

        return $errors;
    }

    /**
     * Valida dados de membro
     */
    public static function validateMember(array $data): array
    {
        $errors = [];

        // Nome
        if (!isset($data['nome']) || empty(trim($data['nome']))) {
            $errors[] = 'Nome é obrigatório';
        } elseif (strlen($data['nome']) < 3) {
            $errors[] = 'Nome muito curto (mínimo 3 caracteres)';
        } elseif (strlen($data['nome']) > 255) {
            $errors[] = 'Nome muito longo (máximo 255 caracteres)';
        }

        // CPF
        if (!isset($data['cpf']) || empty(trim($data['cpf']))) {
            $errors[] = 'CPF é obrigatório';
        } elseif (!self::validateCPF($data['cpf'])) {
            $errors[] = 'CPF inválido';
        }

        // Email
        if (!isset($data['email']) || empty(trim($data['email']))) {
            $errors[] = 'Email é obrigatório';
        } elseif (!self::validateEmail($data['email'])) {
            $errors[] = 'Email inválido';
        }

        // Telefone
        if (!isset($data['telefone']) || empty(trim($data['telefone']))) {
            $errors[] = 'Telefone é obrigatório';
        } elseif (!self::validatePhone($data['telefone'])) {
            $errors[] = 'Telefone inválido';
        }

        // Endereço
        if (!isset($data['endereco']) || empty(trim($data['endereco']))) {
            $errors[] = 'Endereço é obrigatório';
        }

        // Categoria
        $validCategories = ['estudante', 'professor', 'comunidade'];
        if (!isset($data['categoria']) || !in_array($data['categoria'], $validCategories)) {
            $errors[] = 'Categoria inválida';
        }

        return $errors;
    }

    /**
     * Valida string não vazia
     */
    public static function required(mixed $value): bool
    {
        try {
            v::notEmpty()->check($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Valida número inteiro positivo
     */
    public static function positiveInteger(mixed $value): bool
    {
        try {
            v::intVal()->positive()->check($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Valida data no formato Y-m-d
     */
    public static function validateDate(string $date): bool
    {
        try {
            v::date('Y-m-d')->check($date);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Valida URL
     */
    public static function validateUrl(string $url): bool
    {
        try {
            v::url()->check($url);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
