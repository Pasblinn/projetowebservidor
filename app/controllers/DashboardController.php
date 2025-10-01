<?php
/**
 * DashboardController - Controlador da página inicial do sistema
 * Esta é a página principal que o usuário vê após fazer login
 */

class DashboardController {
    
    /**
     * Função para mostrar a página inicial (dashboard)
     */
    public function index() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        
        // Busca dados para mostrar estatísticas básicas
        $books = Database::getAll('books');
        $members = Database::getAll('members');
        $loans = Database::getAll('loans');
        
        // Conta totais
        $total_books = count($books);
        $total_members = count($members);
        $total_loans = count($loans);
        
        // Conta empréstimos ativos (que não foram devolvidos)
        $active_loans = 0;
        foreach ($loans as $loan) {
            if ($loan['status'] === 'ativo') {
                $active_loans++;
            }
        }
        
        // Conta livros disponíveis
        $available_books = 0;
        foreach ($books as $book) {
            $available_books += $book['quantidade_disponivel'];
        }
        
        // Carrega a view do dashboard passando os dados
        include ROOT_PATH . '/app/views/dashboard/index.php';
    }
}
?>