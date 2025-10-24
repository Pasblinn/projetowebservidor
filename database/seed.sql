-- Script de dados iniciais (seed)
-- Sistema de Gerenciamento de Biblioteca

USE biblioteca;

-- Inserir usuários de teste
-- Senha para todos: password (hash gerado com password_hash('password', PASSWORD_DEFAULT))
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
