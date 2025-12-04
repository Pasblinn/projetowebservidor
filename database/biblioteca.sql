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

-- Tabela de tokens de autenticação da API (Laravel Sanctum)
CREATE TABLE IF NOT EXISTS personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX personal_access_tokens_tokenable_type_tokenable_id_index (tokenable_type, tokenable_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Script de dados iniciais (seed)
-- Sistema de Gerenciamento de Biblioteca

USE biblioteca;

-- Inserir usuários de teste
-- Senha para todos: admin123 (hash gerado com password_hash('admin123', PASSWORD_DEFAULT))
INSERT INTO users (username, password, email, nome, tipo, ativo) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@biblioteca.com', 'Administrador', 'admin', TRUE),
('bibliotecario', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bibliotecario@biblioteca.com', 'João Silva', 'bibliotecario', TRUE);

-- Inserir livros de exemplo
INSERT INTO books (titulo, autor, isbn, editora, ano_publicacao, categoria, quantidade_total, quantidade_disponivel, localizacao) VALUES
('Dom Casmurro', 'Machado de Assis', '9788525406626', 'Globo', 1899, 'Literatura Brasileira', 5, 3, 'A-001'),
('O Cortiço', 'Aluísio Azevedo', '9788525406633', 'Ática', 1890, 'Literatura Brasileira', 3, 2, 'A-002'),
('Grande Sertão: Veredas', 'Guimarães Rosa', '9788520923665', 'Nova Fronteira', 1956, 'Literatura Brasileira', 4, 4, 'A-003'),
('1984', 'George Orwell', '9788535914849', 'Companhia das Letras', 1949, 'Ficção Científica', 6, 5, 'B-001'),
('O Senhor dos Anéis', 'J.R.R. Tolkien', '9788533613379', 'Martins Fontes', 1954, 'Fantasia', 8, 6, 'B-002');

-- Inserir membros de exemplo
INSERT INTO members (nome, email, telefone, endereco, cpf, data_nascimento, categoria, ativo) VALUES
('Maria Santos', 'maria@email.com', '(11) 99999-9999', 'Rua das Flores, 123', '123.456.789-00', '1990-05-15', 'estudante', TRUE),
('Pedro Oliveira', 'pedro@email.com', '(11) 88888-8888', 'Av. Principal, 456', '987.654.321-00', '1985-12-20', 'professor', TRUE),
('Ana Costa', 'ana@email.com', '(11) 77777-7777', 'Rua do Comércio, 789', '456.789.123-00', '1995-03-10', 'estudante', TRUE);

-- Inserir empréstimos de exemplo
INSERT INTO loans (member_id, book_id, data_emprestimo, data_prevista_devolucao, data_devolucao, status, usuario_responsavel) VALUES
(1, 1, '2024-10-15', '2024-10-29', NULL, 'ativo', 'bibliotecario'),
(2, 4, '2024-10-10', '2024-10-24', '2024-10-22', 'devolvido', 'bibliotecario'),
(1, 2, '2024-10-18', '2024-11-01', NULL, 'ativo', 'admin');
