<?php
/**
 * LoansController - Controlador para gerenciar empréstimos de livros
 * Este arquivo contém todas as funções para listar, criar, editar e devolver empréstimos
 */

class LoansController {
    
    /**
     * Função para mostrar a lista de empréstimos
     */
    public function index() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Busca todos os empréstimos do array
        $loans = Database::getAll('loans');
        
        // Para cada empréstimo, busca dados do membro e livro
        foreach ($loans as &$loan) {
            // Busca dados do membro
            $member = Database::getById('members', $loan['member_id']);
            $loan['member_name'] = $member ? $member['nome'] : 'Membro não encontrado';
            
            // Busca dados do livro
            $book = Database::getById('books', $loan['book_id']);
            $loan['book_title'] = $book ? $book['titulo'] : 'Livro não encontrado';
        }
        
        // Carrega a view da lista
        include ROOT_PATH . '/app/views/loans/index.php';
    }
    
    /**
     * Função para mostrar o formulário de novo empréstimo
     */
    public function create() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Busca livros disponíveis (quantidade > 0)
        $books = Database::getAll('books');
        $available_books = [];
        foreach ($books as $book) {
            if ($book['quantidade_disponivel'] > 0) {
                $available_books[] = $book;
            }
        }
        
        // Busca membros ativos
        $members = Database::getAll('members');
        $active_members = [];
        foreach ($members as $member) {
            if ($member['ativo']) {
                $active_members[] = $member;
            }
        }
        
        // Carrega o formulário
        include ROOT_PATH . '/app/views/loans/create.php';
    }
    
    /**
     * Função para processar o cadastro de novo empréstimo
     */
    public function store() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Verifica se foi POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'loans');
            exit;
        }
        
        // Array para erros
        $errors = [];
        
        // Pega dados do formulário
        $member_id = (int)($_POST['member_id'] ?? 0);
        $book_id = (int)($_POST['book_id'] ?? 0);
        $data_emprestimo = $_POST['data_emprestimo'] ?? date('Y-m-d');
        $observacoes = trim($_POST['observacoes'] ?? '');
        
        // Validações básicas
        if ($member_id <= 0) {
            $errors[] = 'Selecione um membro válido';
        }
        if ($book_id <= 0) {
            $errors[] = 'Selecione um livro válido';
        }
        if (empty($data_emprestimo)) {
            $errors[] = 'Data de empréstimo é obrigatória';
        }
        
        // Verifica se o membro existe e está ativo
        $member = Database::getById('members', $member_id);
        if (!$member || !$member['ativo']) {
            $errors[] = 'Membro não encontrado ou inativo';
        }
        
        // Verifica se o livro existe e está disponível
        $book = Database::getById('books', $book_id);
        if (!$book || $book['quantidade_disponivel'] <= 0) {
            $errors[] = 'Livro não disponível para empréstimo';
        }
        
        // Se há erros, volta ao formulário
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . 'loans/create');
            exit;
        }
        
        // Calcula data de devolução (14 dias)
        $data_devolucao = date('Y-m-d', strtotime($data_emprestimo . ' +14 days'));
        
        // Prepara dados do empréstimo
        $loan_data = [
            'member_id' => $member_id,
            'book_id' => $book_id,
            'data_emprestimo' => $data_emprestimo,
            'data_prevista_devolucao' => $data_devolucao,
            'data_devolucao' => null, // Ainda não foi devolvido
            'status' => 'ativo',
            'observacoes' => $observacoes,
            'usuario_responsavel' => $_SESSION['username']
        ];
        
        // Salva o empréstimo
        $loan_id = Database::insert('loans', $loan_data);
        
        if ($loan_id) {
            // Diminui quantidade disponível do livro
            $book['quantidade_disponivel']--;
            Database::update('books', $book_id, $book);
            
            $_SESSION['success'] = 'Empréstimo registrado com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao registrar empréstimo'];
        }
        
        header('Location: ' . BASE_URL . 'loans');
        exit;
    }
    
    /**
     * Função para devolver um livro emprestado
     */
    public function return() {
        // Verifica se está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Pega o ID do empréstimo
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            $_SESSION['errors'] = ['ID de empréstimo inválido'];
            header('Location: ' . BASE_URL . 'loans');
            exit;
        }
        
        // Busca o empréstimo
        $loan = Database::getById('loans', $id);
        if (!$loan) {
            $_SESSION['errors'] = ['Empréstimo não encontrado'];
            header('Location: ' . BASE_URL . 'loans');
            exit;
        }
        
        // Verifica se ainda está ativo
        if ($loan['status'] !== 'ativo') {
            $_SESSION['errors'] = ['Este empréstimo já foi finalizado'];
            header('Location: ' . BASE_URL . 'loans');
            exit;
        }
        
        // Atualiza o empréstimo para devolvido
        $loan['data_devolucao'] = date('Y-m-d');
        $loan['status'] = 'devolvido';
        $success = Database::update('loans', $id, $loan);
        
        if ($success) {
            // Aumenta quantidade disponível do livro
            $book = Database::getById('books', $loan['book_id']);
            if ($book) {
                $book['quantidade_disponivel']++;
                Database::update('books', $loan['book_id'], $book);
            }
            
            $_SESSION['success'] = 'Livro devolvido com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao processar devolução'];
        }
        
        header('Location: ' . BASE_URL . 'loans');
        exit;
    }
}
?>