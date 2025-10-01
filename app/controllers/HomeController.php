<?php
/**
 * HomeController - Controlador da página inicial
 * Redireciona para login ou dashboard dependendo do estado da autenticação
 */

class HomeController {
    
    /**
     * Função principal da página inicial
     * Verifica se usuário está logado e redireciona adequadamente
     */
    public function index() {
        // Verifica se o usuário já está logado
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
            // Se está logado, vai para o dashboard
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        } else {
            // Se não está logado, vai para a tela de login
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
    }
}
?>