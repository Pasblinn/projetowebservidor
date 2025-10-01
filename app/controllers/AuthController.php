<?php
/**
 * AuthController - Controlador de Autenticação
 * 
 * Gerencia login, logout e controle de sessão do sistema
 * Implementa validações de segurança e controle de acesso
 */

class AuthController {
    
    /**
     * Exibe a tela de login
     */
    public function index() {
        // Se usuário já está logado, redireciona para dashboard
        if ($this->isAuthenticated()) {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
        
        // Carrega a view de login
        $this->loadView('auth/login');
    }
    
    /**
     * Processa tentativa de login
     */
    public function login() {
        // Verifica se a requisição é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Obtém dados do formulário
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Array para armazenar erros
        $errors = [];
        
        // Validações básicas
        if (empty($username)) {
            $errors[] = 'Nome de usuário é obrigatório';
        }
        
        if (empty($password)) {
            $errors[] = 'Senha é obrigatória';
        }
        
        // Se há erros de validação, retorna para o formulário
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = ['username' => $username];
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Tenta autenticar o usuário
        $user = User::authenticate($username, $password);
        
        if ($user) {
            // Login bem-sucedido
            $this->createUserSession($user);
            
            // Mensagem de sucesso
            $_SESSION['success'] = 'Login realizado com sucesso! Bem-vindo(a), ' . $user['nome'];
            
            // Redireciona para dashboard
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        } else {
            // Login falhou
            $_SESSION['errors'] = ['Usuário ou senha inválidos'];
            $_SESSION['old_input'] = ['username' => $username];
            
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
    }
    
    /**
     * Realiza logout do sistema
     */
    public function logout() {
        // Destroi todas as variáveis de sessão
        session_unset();
        session_destroy();
        
        // Inicia nova sessão para mostrar mensagem
        session_start();
        $_SESSION['success'] = 'Logout realizado com sucesso!';
        
        // Redireciona para página de login
        header('Location: ' . BASE_URL . 'auth');
        exit;
    }
    
    /**
     * Cria sessão do usuário logado
     * @param array $user Dados do usuário
     */
    private function createUserSession($user) {
        // Regenera ID da sessão por segurança
        session_regenerate_id(true);
        
        // Armazena dados do usuário na sessão
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['tipo'] = $user['tipo'];
        $_SESSION['last_activity'] = time();
        
        // Flag indicando que usuário está autenticado
        $_SESSION['authenticated'] = true;
    }
    
    /**
     * Verifica se usuário está autenticado
     * @return bool True se autenticado
     */
    private function isAuthenticated() {
        // Verifica se existe sessão ativa
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            return false;
        }
        
        // Verifica timeout da sessão
        if (isset($_SESSION['last_activity'])) {
            $timeout = time() - $_SESSION['last_activity'];
            if ($timeout > SESSION_TIMEOUT) {
                // Sessão expirou
                $this->logout();
                return false;
            }
        }
        
        // Atualiza timestamp da atividade
        $_SESSION['last_activity'] = time();
        
        return true;
    }
    
    /**
     * Middleware para verificar autenticação
     * Usado em outros controladores para proteger páginas
     */
    public static function requireAuth() {
        // Verifica se usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            $_SESSION['errors'] = ['Você precisa fazer login para acessar esta página'];
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Verifica timeout da sessão
        if (isset($_SESSION['last_activity'])) {
            $timeout = time() - $_SESSION['last_activity'];
            if ($timeout > SESSION_TIMEOUT) {
                // Sessão expirou
                session_unset();
                session_destroy();
                session_start();
                $_SESSION['errors'] = ['Sua sessão expirou. Faça login novamente.'];
                header('Location: ' . BASE_URL . 'auth');
                exit;
            }
        }
        
        // Atualiza timestamp da atividade
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Verifica se usuário tem permissão de administrador
     */
    public static function requireAdmin() {
        self::requireAuth();
        
        if ($_SESSION['tipo'] !== 'admin') {
            $_SESSION['errors'] = ['Você não tem permissão para acessar esta área'];
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
    }
    
    /**
     * Carrega uma view
     * @param string $view Nome da view
     * @param array $data Dados para passar para a view
     */
    private function loadView($view, $data = []) {
        // Extrai variáveis para a view
        extract($data);
        
        // Inclui o arquivo da view
        $view_file = ROOT_PATH . '/app/views/' . $view . '.php';
        
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            die("View não encontrada: $view");
        }
    }
}
?>