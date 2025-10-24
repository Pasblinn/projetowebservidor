-- Script de criação do banco de dados
-- Sistema de Gerenciamento de Biblioteca

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca;

-- Tabela de usuários do sistema (para autenticação)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    tipo ENUM('admin', 'bibliotecario') DEFAULT 'bibliotecario',
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de livros
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(150) NOT NULL,
    isbn VARCHAR(20) UNIQUE NOT NULL,
    editora VARCHAR(100) NOT NULL,
    ano_publicacao INT NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    quantidade_total INT NOT NULL DEFAULT 1,
    quantidade_disponivel INT NOT NULL DEFAULT 1,
    localizacao VARCHAR(50) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_titulo (titulo),
    INDEX idx_autor (autor),
    INDEX idx_isbn (isbn),
    INDEX idx_categoria (categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de membros da biblioteca
CREATE TABLE IF NOT EXISTS members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    endereco VARCHAR(200) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    data_nascimento DATE NOT NULL,
    categoria ENUM('estudante', 'professor', 'comunidade') NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nome (nome),
    INDEX idx_email (email),
    INDEX idx_cpf (cpf),
    INDEX idx_categoria (categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de empréstimos
CREATE TABLE IF NOT EXISTS loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    book_id INT NOT NULL,
    data_emprestimo DATE NOT NULL,
    data_prevista_devolucao DATE NOT NULL,
    data_devolucao DATE NULL,
    status ENUM('ativo', 'devolvido', 'atrasado') DEFAULT 'ativo',
    observacoes TEXT,
    usuario_responsavel VARCHAR(50) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE RESTRICT,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE RESTRICT,
    INDEX idx_member_id (member_id),
    INDEX idx_book_id (book_id),
    INDEX idx_status (status),
    INDEX idx_data_emprestimo (data_emprestimo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
