<?php
/**
 * MembersController - Controlador para gerenciar membros da biblioteca
 * Este arquivo contém todas as funções para listar, criar, editar e excluir membros
 */

class MembersController {
    
    /**
     * Função para mostrar a lista de membros
     */
    public function index() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Busca todos os membros do array que simula o banco
        $members = Database::getAll('members');
        
        // Carrega a view que mostra a lista
        include ROOT_PATH . '/app/views/members/index.php';
    }
    
    /**
     * Função para mostrar o formulário de cadastro de membro
     */
    public function create() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Carrega a view do formulário
        include ROOT_PATH . '/app/views/members/create.php';
    }
    
    /**
     * Função para processar o cadastro de um novo membro
     */
    public function store() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Verifica se foi enviado via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'members');
            exit;
        }
        
        // Array para armazenar erros
        $errors = [];
        
        // Pega os dados do formulário
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $endereco = trim($_POST['endereco'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $data_nascimento = $_POST['data_nascimento'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        
        // Validações básicas
        if (empty($nome)) {
            $errors[] = 'Nome é obrigatório';
        }
        if (empty($email)) {
            $errors[] = 'Email é obrigatório';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email deve ter formato válido';
        }
        if (empty($telefone)) {
            $errors[] = 'Telefone é obrigatório';
        }
        if (empty($endereco)) {
            $errors[] = 'Endereço é obrigatório';
        }
        if (empty($cpf)) {
            $errors[] = 'CPF é obrigatório';
        }
        if (empty($data_nascimento)) {
            $errors[] = 'Data de nascimento é obrigatória';
        }
        if (empty($categoria)) {
            $errors[] = 'Categoria é obrigatória';
        }
        
        // Verifica se CPF já existe
        $existing_members = Database::getAll('members');
        foreach ($existing_members as $member) {
            if ($member['cpf'] === $cpf) {
                $errors[] = 'CPF já cadastrado no sistema';
                break;
            }
        }
        
        // Verifica se email já existe
        foreach ($existing_members as $member) {
            if ($member['email'] === $email) {
                $errors[] = 'Email já cadastrado no sistema';
                break;
            }
        }
        
        // Se há erros, volta para o formulário
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . 'members/create');
            exit;
        }
        
        // Prepara os dados para salvar
        $data = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'endereco' => $endereco,
            'cpf' => $cpf,
            'data_nascimento' => $data_nascimento,
            'categoria' => $categoria,
            'ativo' => true,
            'data_cadastro' => date('Y-m-d')
        ];
        
        // Salva no array
        $member_id = Database::insert('members', $data);
        
        // Verifica se salvou
        if ($member_id) {
            $_SESSION['success'] = 'Membro cadastrado com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao cadastrar membro'];
        }
        
        // Redireciona para a lista
        header('Location: ' . BASE_URL . 'members');
        exit;
    }
    
    /**
     * Função para mostrar o formulário de edição
     */
    public function edit() {
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
            header('Location: ' . BASE_URL . 'members');
            exit;
        }
        
        // Busca o membro
        $member = Database::getById('members', $id);
        
        // Verifica se existe
        if (!$member) {
            $_SESSION['errors'] = ['Membro não encontrado'];
            header('Location: ' . BASE_URL . 'members');
            exit;
        }
        
        // Carrega a view de edição
        include ROOT_PATH . '/app/views/members/edit.php';
    }
    
    /**
     * Função para processar a atualização de um membro
     */
    public function update() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Verifica se foi POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'members');
            exit;
        }
        
        // Pega o ID
        $id = (int)($_POST['id'] ?? 0);
        
        // Verifica se é válido
        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            header('Location: ' . BASE_URL . 'members');
            exit;
        }
        
        // Array para erros
        $errors = [];
        
        // Pega os dados do formulário
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $endereco = trim($_POST['endereco'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $data_nascimento = $_POST['data_nascimento'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        
        // Validações (iguais ao cadastro)
        if (empty($nome)) {
            $errors[] = 'Nome é obrigatório';
        }
        if (empty($email)) {
            $errors[] = 'Email é obrigatório';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email deve ter formato válido';
        }
        if (empty($telefone)) {
            $errors[] = 'Telefone é obrigatório';
        }
        if (empty($endereco)) {
            $errors[] = 'Endereço é obrigatório';
        }
        if (empty($cpf)) {
            $errors[] = 'CPF é obrigatório';
        }
        if (empty($data_nascimento)) {
            $errors[] = 'Data de nascimento é obrigatória';
        }
        if (empty($categoria)) {
            $errors[] = 'Categoria é obrigatória';
        }
        
        // Verifica se CPF já existe (exceto o próprio registro)
        $existing_members = Database::getAll('members');
        foreach ($existing_members as $member) {
            if ($member['cpf'] === $cpf && $member['id'] != $id) {
                $errors[] = 'CPF já cadastrado no sistema';
                break;
            }
        }
        
        // Verifica se email já existe (exceto o próprio registro)
        foreach ($existing_members as $member) {
            if ($member['email'] === $email && $member['id'] != $id) {
                $errors[] = 'Email já cadastrado no sistema';
                break;
            }
        }
        
        // Se há erros
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . 'members/edit?id=' . $id);
            exit;
        }
        
        // Prepara dados para atualizar
        $data = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'endereco' => $endereco,
            'cpf' => $cpf,
            'data_nascimento' => $data_nascimento,
            'categoria' => $categoria
        ];
        
        // Atualiza no array
        $success = Database::update('members', $id, $data);
        
        // Verifica se atualizou
        if ($success) {
            $_SESSION['success'] = 'Membro atualizado com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao atualizar membro'];
        }
        
        // Redireciona
        header('Location: ' . BASE_URL . 'members');
        exit;
    }
    
    /**
     * Função para excluir um membro
     */
    public function delete() {
        // Verifica se está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Pega o ID
        $id = (int)($_GET['id'] ?? 0);
        
        // Verifica se é válido
        if ($id <= 0) {
            $_SESSION['errors'] = ['ID inválido'];
            header('Location: ' . BASE_URL . 'members');
            exit;
        }
        
        // Busca o membro
        $member = Database::getById('members', $id);
        if (!$member) {
            $_SESSION['errors'] = ['Membro não encontrado'];
            header('Location: ' . BASE_URL . 'members');
            exit;
        }
        
        // Remove
        $success = Database::delete('members', $id);
        
        // Verifica se removeu
        if ($success) {
            $_SESSION['success'] = 'Membro removido com sucesso!';
        } else {
            $_SESSION['errors'] = ['Erro ao remover membro'];
        }
        
        // Volta para lista
        header('Location: ' . BASE_URL . 'members');
        exit;
    }
}
?>