<?php
/**
 * BooksController - Controlador para gerenciar livros
 * Este arquivo contém todas as funções para listar, criar, editar e excluir livros
 */

class BooksController {
    
    /**
     * Função para mostrar a lista de livros
     */
    public function index() {
        // Verifica se o usuário está logado, se não redireciona para login
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Busca todos os livros do array que simula o banco de dados
        $books = Database::getAll('books');
        
        // Carrega a view que mostra a lista de livros
        include ROOT_PATH . '/app/views/books/index.php';
    }
    
    /**
     * Função para mostrar o formulário de cadastro de livro
     */
    public function create() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Carrega a view com o formulário de cadastro
        include ROOT_PATH . '/app/views/books/create.php';
    }
    
    /**
     * Função para processar o cadastro de um novo livro
     */
    public function store() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Verifica se a requisição foi feita via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'books');
            exit;
        }
        
        // Cria um array para armazenar os erros de validação
        $errors = [];
        
        // Pega os dados enviados do formulário e remove espaços em branco
        $titulo = trim($_POST['titulo'] ?? '');
        $autor = trim($_POST['autor'] ?? '');
        $isbn = trim($_POST['isbn'] ?? '');
        $editora = trim($_POST['editora'] ?? '');
        $ano_publicacao = $_POST['ano_publicacao'] ?? '';
        $categoria = trim($_POST['categoria'] ?? '');
        $quantidade_total = $_POST['quantidade_total'] ?? '';
        $localizacao = trim($_POST['localizacao'] ?? '');
        
        // Validações básicas - verifica se os campos obrigatórios foram preenchidos
        if (empty($titulo)) {
            $errors[] = 'Título é obrigatório';
        }
        if (empty($autor)) {
            $errors[] = 'Autor é obrigatório';
        }
        if (empty($isbn)) {
            $errors[] = 'ISBN é obrigatório';
        }
        if (empty($editora)) {
            $errors[] = 'Editora é obrigatória';
        }
        if (empty($ano_publicacao) || !is_numeric($ano_publicacao)) {
            $errors[] = 'Ano de publicação deve ser um número';
        }
        if (empty($categoria)) {
            $errors[] = 'Categoria é obrigatória';
        }
        if (empty($quantidade_total) || !is_numeric($quantidade_total) || $quantidade_total < 1) {
            $errors[] = 'Quantidade deve ser um número maior que zero';
        }
        if (empty($localizacao)) {
            $errors[] = 'Localização é obrigatória';
        }
        
        // Se existem erros, volta para o formulário mostrando os erros
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST; // Guarda os dados para mostrar no formulário
            header('Location: ' . BASE_URL . 'books/create');
            exit;
        }
        
        // Se não há erros, prepara os dados para salvar
        $data = [
            'titulo' => $titulo,
            'autor' => $autor,
            'isbn' => $isbn,
            'editora' => $editora,
            'ano_publicacao' => (int)$ano_publicacao,
            'categoria' => $categoria,
            'quantidade_total' => (int)$quantidade_total,
            'quantidade_disponivel' => (int)$quantidade_total, // Inicialmente disponível = total
            'localizacao' => $localizacao,
            'data_cadastro' => date('Y-m-d') // Data atual
        ];
        
        // Salva os dados no array que simula o banco
        $book_id = Database::insert('books', $data);
        
        // Verifica se salvou com sucesso
        if ($book_id) {
            $_SESSION['success'] = 'Livro cadastrado com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao cadastrar livro'];
        }
        
        // Redireciona para a lista de livros
        header('Location: ' . BASE_URL . 'books');
        exit;
    }
    
    /**
     * Função para mostrar o formulário de edição de livro
     */
    public function edit() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Pega o ID do livro da URL
        $id = (int)($_GET['id'] ?? 0);
        
        // Verifica se o ID é válido
        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            header('Location: ' . BASE_URL . 'books');
            exit;
        }
        
        // Busca o livro pelo ID
        $book = Database::getById('books', $id);
        
        // Verifica se o livro existe
        if (!$book) {
            $_SESSION['errors'] = ['Livro não encontrado'];
            header('Location: ' . BASE_URL . 'books');
            exit;
        }
        
        // Carrega a view de edição passando os dados do livro
        include ROOT_PATH . '/app/views/books/edit.php';
    }
    
    /**
     * Função para processar a atualização de um livro
     */
    public function update() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Verifica se a requisição foi via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'books');
            exit;
        }
        
        // Pega o ID do livro
        $id = (int)($_POST['id'] ?? 0);
        
        // Verifica se o ID é válido
        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            header('Location: ' . BASE_URL . 'books');
            exit;
        }
        
        // Array para armazenar erros
        $errors = [];
        
        // Pega os dados do formulário
        $titulo = trim($_POST['titulo'] ?? '');
        $autor = trim($_POST['autor'] ?? '');
        $isbn = trim($_POST['isbn'] ?? '');
        $editora = trim($_POST['editora'] ?? '');
        $ano_publicacao = $_POST['ano_publicacao'] ?? '';
        $categoria = trim($_POST['categoria'] ?? '');
        $quantidade_total = $_POST['quantidade_total'] ?? '';
        $localizacao = trim($_POST['localizacao'] ?? '');
        
        // Validações (iguais ao cadastro)
        if (empty($titulo)) {
            $errors[] = 'Título é obrigatório';
        }
        if (empty($autor)) {
            $errors[] = 'Autor é obrigatório';  
        }
        if (empty($isbn)) {
            $errors[] = 'ISBN é obrigatório';
        }
        if (empty($editora)) {
            $errors[] = 'Editora é obrigatória';
        }
        if (empty($ano_publicacao) || !is_numeric($ano_publicacao)) {
            $errors[] = 'Ano de publicação deve ser um número';
        }
        if (empty($categoria)) {
            $errors[] = 'Categoria é obrigatória';
        }
        if (empty($quantidade_total) || !is_numeric($quantidade_total) || $quantidade_total < 1) {
            $errors[] = 'Quantidade deve ser um número maior que zero';
        }
        if (empty($localizacao)) {
            $errors[] = 'Localização é obrigatória';
        }
        
        // Se há erros, volta para o formulário de edição
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . 'books/edit?id=' . $id);
            exit;
        }
        
        // Busca o livro atual para manter alguns dados
        $current_book = Database::getById('books', $id);
        
        // Prepara os dados atualizados
        $data = [
            'titulo' => $titulo,
            'autor' => $autor,
            'isbn' => $isbn,
            'editora' => $editora,
            'ano_publicacao' => (int)$ano_publicacao,
            'categoria' => $categoria,
            'quantidade_total' => (int)$quantidade_total,
            'quantidade_disponivel' => $current_book['quantidade_disponivel'], // Mantém a disponível
            'localizacao' => $localizacao
        ];
        
        // Atualiza o livro no array
        $success = Database::update('books', $id, $data);
        
        // Verifica se atualizou com sucesso
        if ($success) {
            $_SESSION['success'] = 'Livro atualizado com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao atualizar livro'];
        }
        
        // Redireciona para a lista
        header('Location: ' . BASE_URL . 'books');
        exit;
    }
    
    /**
     * Função para excluir um livro
     */
    public function delete() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Pega o ID da URL
        $id = (int)($_GET['id'] ?? 0);
        
        // Verifica se o ID é válido
        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            header('Location: ' . BASE_URL . 'books');
            exit;
        }
        
        // Busca o livro
        $book = Database::getById('books', $id);
        if (!$book) {
            $_SESSION['errors'] = ['Livro não encontrado'];
            header('Location: ' . BASE_URL . 'books');
            exit;
        }
        
        // Remove o livro
        $success = Database::delete('books', $id);
        
        // Verifica se removeu com sucesso
        if ($success) {
            $_SESSION['success'] = 'Livro removido com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao remover livro'];
        }
        
        // Volta para a lista
        header('Location: ' . BASE_URL . 'books');
        exit;
    }
}
?>