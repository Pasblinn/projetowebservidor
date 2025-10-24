<?php
/**
 * View do Dashboard - Página inicial do sistema
 * Mostra estatísticas e links de navegação rápida
 */

// Define o título da página
$title = 'Dashboard';

// Define valores padrão se não existirem
$totalBooks = $totalBooks ?? 0;
$totalMembers = $totalMembers ?? 0;
$totalLoans = $totalLoans ?? 0;
$activeLoans = $activeLoans ?? 0;
$overdueLoans = $overdueLoans ?? 0;
$availableBooks = $availableBooks ?? 0;

// Inclui o cabeçalho da página
include dirname(__DIR__) . '/layout/header.php';
?>

<!-- Cabeçalho da página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>🏠 Dashboard - Sistema de Biblioteca</h2>
    <small class="text-muted">
        Bem-vindo(a), <strong><?php echo escape($_SESSION['nome'] ?? 'Usuário'); ?></strong>
    </small>
</div>

<!-- Cards de estatísticas -->
<div class="row mb-5">

    <!-- Estatística de Livros -->
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-primary">
            <div class="card-body text-center text-white">
                <span class="stat-number"><?php echo $totalBooks; ?></span>
                <div class="stat-label">📚 Livros Cadastrados</div>
                <small>Total no sistema</small>
            </div>
        </div>
    </div>

    <!-- Estatística de Membros -->
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-success">
            <div class="card-body text-center text-white">
                <span class="stat-number"><?php echo $totalMembers; ?></span>
                <div class="stat-label">👥 Membros Ativos</div>
                <small>Usuários cadastrados</small>
            </div>
        </div>
    </div>

    <!-- Estatística de Empréstimos -->
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-warning">
            <div class="card-body text-center text-white">
                <span class="stat-number"><?php echo $activeLoans; ?></span>
                <div class="stat-label">📖 Empréstimos Ativos</div>
                <small>Livros emprestados</small>
            </div>
        </div>
    </div>

    <!-- Estatística de Livros Disponíveis -->
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-info">
            <div class="card-body text-center text-white">
                <span class="stat-number"><?php echo $availableBooks; ?></span>
                <div class="stat-label">📗 Livros Disponíveis</div>
                <small>Para empréstimo</small>
            </div>
        </div>
    </div>

</div>

<!-- Links de Navegação Rápida -->
<div class="row">

    <!-- Seção de Livros -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📚 Gerenciar Livros</h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    Adicione, edite ou remova livros do acervo da biblioteca.
                    Controle estoque e disponibilidade.
                </p>
                <div class="d-grid gap-2">
                    <a href="<?php echo base_url('books'); ?>" class="btn btn-primary">
                        📋 Ver Todos os Livros
                    </a>
                    <a href="<?php echo base_url('books/create'); ?>" class="btn btn-outline-primary">
                        ➕ Cadastrar Novo Livro
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Membros -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">👥 Gerenciar Membros</h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    Cadastre e gerencie os membros da biblioteca.
                    Estudantes, professores e funcionários.
                </p>
                <div class="d-grid gap-2">
                    <a href="<?php echo base_url('members'); ?>" class="btn btn-success">
                        👥 Ver Todos os Membros
                    </a>
                    <a href="<?php echo base_url('members/create'); ?>" class="btn btn-outline-success">
                        ➕ Cadastrar Novo Membro
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Empréstimos -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">📖 Empréstimos</h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    Gerencie empréstimos e devoluções de livros.
                    Controle prazos e histórico.
                </p>
                <div class="d-grid gap-2">
                    <a href="<?php echo base_url('loans'); ?>" class="btn btn-warning">
                        📋 Ver Empréstimos
                    </a>
                    <a href="<?php echo base_url('loans/create'); ?>" class="btn btn-outline-warning">
                        ➕ Novo Empréstimo
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Informações do Sistema -->
<div class="card mt-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">ℹ️ Informações do Sistema</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>📊 Resumo Atual:</h6>
                <ul>
                    <li><strong><?php echo $totalBooks; ?></strong> livros cadastrados no sistema</li>
                    <li><strong><?php echo $totalMembers; ?></strong> membros ativos</li>
                    <li><strong><?php echo $activeLoans; ?></strong> empréstimos em andamento</li>
                    <li><strong><?php echo $availableBooks; ?></strong> exemplares disponíveis</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>👤 Sua Sessão:</h6>
                <ul>
                    <li><strong>Usuário:</strong> <?php echo escape($_SESSION['nome'] ?? ''); ?></li>
                    <li><strong>Tipo:</strong> <?php echo ucfirst($_SESSION['tipo'] ?? 'user'); ?></li>
                    <li><strong>Email:</strong> <?php echo escape($_SESSION['email'] ?? ''); ?></li>
                    <li><strong>Login:</strong> <?php echo escape($_SESSION['username'] ?? ''); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
// Inclui o rodapé da página
include dirname(__DIR__) . '/layout/footer.php';
?>
