<?php

namespace App\Controllers;

use App\Models\Repositories\BookRepository;
use App\Models\Entities\Book;
use DateTime;

/**
 * BooksController - Controlador para gerenciar livros
 */
class BooksController extends BaseController
{
    private BookRepository $bookRepository;

    public function __construct()
    {
        $this->bookRepository = new BookRepository();
    }

    /**
     * Lista todos os livros
     */
    public function index(): void
    {
        AuthController::requireAuth();

        $books = $this->bookRepository->getAllBooks();

        $this->loadView('books/index', ['books' => $books]);
    }

    /**
     * Exibe formulário de criação de livro
     */
    public function create(): void
    {
        AuthController::requireAuth();

        $this->loadView('books/create');
    }

    /**
     * Processa criação de novo livro
     */
    public function store(): void
    {
        AuthController::requireAuth();

        if (!$this->isPost()) {
            redirect('books');
        }

        $errors = [];

        // Obtém dados do formulário
        $titulo = trim($this->getPost('titulo', ''));
        $autor = trim($this->getPost('autor', ''));
        $isbn = trim($this->getPost('isbn', ''));
        $editora = trim($this->getPost('editora', ''));
        $anoPublicacao = $this->getPost('ano_publicacao', '');
        $categoria = trim($this->getPost('categoria', ''));
        $quantidadeTotal = $this->getPost('quantidade_total', '');
        $localizacao = trim($this->getPost('localizacao', ''));

        // Validações
        if (empty($titulo)) {
            $errors[] = 'Título é obrigatório';
        }
        if (empty($autor)) {
            $errors[] = 'Autor é obrigatório';
        }
        if (empty($isbn)) {
            $errors[] = 'ISBN é obrigatório';
        } elseif ($this->bookRepository->isbnExists($isbn)) {
            $errors[] = 'ISBN já cadastrado';
        }
        if (empty($editora)) {
            $errors[] = 'Editora é obrigatória';
        }
        if (empty($anoPublicacao) || !is_numeric($anoPublicacao)) {
            $errors[] = 'Ano de publicação deve ser um número';
        }
        if (empty($categoria)) {
            $errors[] = 'Categoria é obrigatória';
        }
        if (empty($quantidadeTotal) || !is_numeric($quantidadeTotal) || $quantidadeTotal < 1) {
            $errors[] = 'Quantidade deve ser um número maior que zero';
        }
        if (empty($localizacao)) {
            $errors[] = 'Localização é obrigatória';
        }

        // Se há erros, volta para o formulário
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            redirect('books/create');
        }

        // Cria entidade Book
        $book = new Book(
            $titulo,
            $autor,
            $isbn,
            $editora,
            (int)$anoPublicacao,
            $categoria,
            (int)$quantidadeTotal,
            (int)$quantidadeTotal, // Disponível = Total inicialmente
            $localizacao
        );

        // Salva no banco
        $bookId = $this->bookRepository->create($book);

        if ($bookId) {
            $_SESSION['success'] = 'Livro cadastrado com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao cadastrar livro'];
        }

        redirect('books');
    }

    /**
     * Exibe formulário de edição de livro
     */
    public function edit(): void
    {
        AuthController::requireAuth();

        $id = (int)$this->getQuery('id', 0);

        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            redirect('books');
        }

        $book = $this->bookRepository->findBookById($id);

        if (!$book) {
            $_SESSION['errors'] = ['Livro não encontrado'];
            redirect('books');
        }

        $this->loadView('books/edit', ['book' => $book]);
    }

    /**
     * Processa atualização de livro
     */
    public function update(): void
    {
        AuthController::requireAuth();

        if (!$this->isPost()) {
            redirect('books');
        }

        $id = (int)$this->getPost('id', 0);

        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            redirect('books');
        }

        $errors = [];

        // Obtém dados do formulário
        $titulo = trim($this->getPost('titulo', ''));
        $autor = trim($this->getPost('autor', ''));
        $isbn = trim($this->getPost('isbn', ''));
        $editora = trim($this->getPost('editora', ''));
        $anoPublicacao = $this->getPost('ano_publicacao', '');
        $categoria = trim($this->getPost('categoria', ''));
        $quantidadeTotal = $this->getPost('quantidade_total', '');
        $localizacao = trim($this->getPost('localizacao', ''));

        // Validações
        if (empty($titulo)) {
            $errors[] = 'Título é obrigatório';
        }
        if (empty($autor)) {
            $errors[] = 'Autor é obrigatório';
        }
        if (empty($isbn)) {
            $errors[] = 'ISBN é obrigatório';
        } elseif ($this->bookRepository->isbnExists($isbn, $id)) {
            $errors[] = 'ISBN já cadastrado para outro livro';
        }
        if (empty($editora)) {
            $errors[] = 'Editora é obrigatória';
        }
        if (empty($anoPublicacao) || !is_numeric($anoPublicacao)) {
            $errors[] = 'Ano de publicação deve ser um número';
        }
        if (empty($categoria)) {
            $errors[] = 'Categoria é obrigatória';
        }
        if (empty($quantidadeTotal) || !is_numeric($quantidadeTotal) || $quantidadeTotal < 1) {
            $errors[] = 'Quantidade deve ser um número maior que zero';
        }
        if (empty($localizacao)) {
            $errors[] = 'Localização é obrigatória';
        }

        // Se há erros, volta para o formulário
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            redirect('books/edit?id=' . $id);
        }

        // Busca livro atual
        $currentBook = $this->bookRepository->findBookById($id);

        if (!$currentBook) {
            $_SESSION['errors'] = ['Livro não encontrado'];
            redirect('books');
        }

        // Atualiza entidade
        $currentBook->setTitulo($titulo);
        $currentBook->setAutor($autor);
        $currentBook->setIsbn($isbn);
        $currentBook->setEditora($editora);
        $currentBook->setAnoPublicacao((int)$anoPublicacao);
        $currentBook->setCategoria($categoria);
        $currentBook->setQuantidadeTotal((int)$quantidadeTotal);
        $currentBook->setLocalizacao($localizacao);

        // Salva no banco
        $success = $this->bookRepository->update($currentBook);

        if ($success) {
            $_SESSION['success'] = 'Livro atualizado com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao atualizar livro'];
        }

        redirect('books');
    }

    /**
     * Deleta um livro
     */
    public function delete(): void
    {
        AuthController::requireAuth();

        $id = (int)$this->getQuery('id', 0);

        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            redirect('books');
        }

        $book = $this->bookRepository->findBookById($id);

        if (!$book) {
            $_SESSION['errors'] = ['Livro não encontrado'];
            redirect('books');
        }

        $success = $this->bookRepository->delete($id);

        if ($success) {
            $_SESSION['success'] = 'Livro removido com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao remover livro'];
        }

        redirect('books');
    }
}
