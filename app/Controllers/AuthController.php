<?php

namespace App\Controllers;

use App\Models\Repositories\UserRepository;

/**
 * AuthController - Controlador de Autenticação
 * Gerencia login, logout e controle de sessão do sistema
 */
class AuthController extends BaseController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * Exibe a tela de login
     */
    public function index(): void
    {
        // Se usuário já está logado, redireciona para dashboard
        if ($this->isAuthenticated()) {
            redirect('dashboard');
        }

        // Carrega a view de login
        $this->loadView('auth/login');
    }

    /**
     * Processa tentativa de login
     */
    public function login(): void
    {
        // Verifica se a requisição é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('auth');
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
            redirect('auth');
        }

        // Tenta buscar o usuário
        $user = $this->userRepository->findByUsername($username);

        // Verifica se usuário existe e senha está correta
        if ($user && $user->isAtivo() && password_verify($password, $user->getPassword())) {
            // Login bem-sucedido
            $this->createUserSession($user->toArray());

            // Mensagem de sucesso
            $_SESSION['success'] = 'Login realizado com sucesso! Bem-vindo(a), ' . $user->getNome();

            // Redireciona para dashboard
            redirect('dashboard');
        } else {
            // Login falhou
            $_SESSION['errors'] = ['Usuário ou senha inválidos'];
            $_SESSION['old_input'] = ['username' => $username];

            redirect('auth');
        }
    }

    /**
     * Realiza logout do sistema
     */
    public function logout(): void
    {
        // Destroi todas as variáveis de sessão
        session_unset();
        session_destroy();

        // Inicia nova sessão para mostrar mensagem
        session_start();
        $_SESSION['success'] = 'Logout realizado com sucesso!';

        // Redireciona para página de login
        redirect('auth');
    }

    /**
     * Cria sessão do usuário logado
     */
    private function createUserSession(array $user): void
    {
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
     */
    private function isAuthenticated(): bool
    {
        // Verifica se existe sessão ativa
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            return false;
        }

        // Verifica timeout da sessão
        if (isset($_SESSION['last_activity'])) {
            $timeout = time() - $_SESSION['last_activity'];
            $sessionTimeout = (int)env('SESSION_TIMEOUT', 3600);

            if ($timeout > $sessionTimeout) {
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
    public static function requireAuth(): void
    {
        // Verifica se usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            $_SESSION['errors'] = ['Você precisa fazer login para acessar esta página'];
            redirect('auth');
        }

        // Verifica timeout da sessão
        if (isset($_SESSION['last_activity'])) {
            $timeout = time() - $_SESSION['last_activity'];
            $sessionTimeout = (int)env('SESSION_TIMEOUT', 3600);

            if ($timeout > $sessionTimeout) {
                // Sessão expirou
                session_unset();
                session_destroy();
                session_start();
                $_SESSION['errors'] = ['Sua sessão expirou. Faça login novamente.'];
                redirect('auth');
            }
        }

        // Atualiza timestamp da atividade
        $_SESSION['last_activity'] = time();
    }

    /**
     * Verifica se usuário tem permissão de administrador
     */
    public static function requireAdmin(): void
    {
        self::requireAuth();

        if ($_SESSION['tipo'] !== 'admin') {
            $_SESSION['errors'] = ['Você não tem permissão para acessar esta área'];
            redirect('dashboard');
        }
    }
}
