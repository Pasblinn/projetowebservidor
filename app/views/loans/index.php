<?php
/**
 * View para listar todos os empréstimos
 * Esta página mostra uma tabela com todos os empréstimos do sistema
 */

// Define o título da página
$title = 'Lista de Empréstimos';

// Inclui o cabeçalho da página
include ROOT_PATH . '/app/views/layout/header.php';
?>

<!-- Título da página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📖 Lista de Empréstimos</h2>
    <!-- Botão para novo empréstimo -->
    <a href="<?php echo base_url('loans/create'); ?> " class="btn btn-warning">
        ➕ Novo Empréstimo
    </a>
</div>

<!-- Verifica se existem empréstimos -->
<?php if (empty($loans)): ?>
    <!-- Se não há empréstimos -->
    <div class="alert alert-info">
        <h4>Nenhum empréstimo registrado</h4>
        <p>Clique no botão "Novo Empréstimo" para registrar o primeiro empréstimo do sistema.</p>
    </div>
<?php else: ?>
    <!-- Se há empréstimos, mostra a tabela -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <!-- Cabeçalho da tabela -->
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Membro</th>
                    <th>Livro</th>
                    <th>Data Empréstimo</th>
                    <th>Data Prev. Devolução</th>
                    <th>Data Devolução</th>
                    <th>Status</th>
                    <th>Responsável</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <!-- Corpo da tabela -->
            <tbody>
                <?php 
                // Loop para mostrar cada empréstimo
                foreach ($loans as $loan): 
                    // Verifica se está em atraso
                    $hoje = date('Y-m-d');
                    $em_atraso = ($loan['status'] === 'ativo' && $loan['data_prevista_devolucao'] < $hoje);
                ?>
                    <tr class="<?php echo $em_atraso ? 'table-danger' : ''; ?>">
                        <!-- ID do empréstimo -->
                        <td><?php echo $loan['id']; ?></td>
                        
                        <!-- Nome do membro -->
                        <td><?php echo htmlspecialchars($loan['member_name']); ?></td>
                        
                        <!-- Título do livro -->
                        <td><?php echo htmlspecialchars($loan['book_title']); ?></td>
                        
                        <!-- Data de empréstimo -->
                        <td><?php echo date('d/m/Y', strtotime($loan['data_emprestimo'])); ?></td>
                        
                        <!-- Data prevista para devolução -->
                        <td>
                            <?php echo date('d/m/Y', strtotime($loan['data_prevista_devolucao'])); ?>
                            <?php if ($em_atraso): ?>
                                <span class="badge bg-danger ms-1">ATRASADO</span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- Data real de devolução -->
                        <td>
                            <?php if ($loan['data_devolucao']): ?>
                                <?php echo date('d/m/Y', strtotime($loan['data_devolucao'])); ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- Status com cores -->
                        <td>
                            <?php if ($loan['status'] === 'ativo'): ?>
                                <?php if ($em_atraso): ?>
                                    <span class="badge bg-danger">Em Atraso</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Ativo</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge bg-success">Devolvido</span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- Usuário responsável -->
                        <td>
                            <small><?php echo htmlspecialchars($loan['usuario_responsavel']); ?></small>
                        </td>
                        
                        <!-- Ações -->
                        <td>
                            <?php if ($loan['status'] === 'ativo'): ?>
                                <!-- Botão para devolver -->
                                <a href="<?php echo base_url('loans/devolver?id=' . $loan['id']); ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Confirma a devolução deste livro?')">
                                    ✅ Devolver
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Estatísticas -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h5>
                        <?php 
                        $ativos = 0;
                        foreach ($loans as $loan) {
                            if ($loan['status'] === 'ativo') $ativos++;
                        }
                        echo $ativos;
                        ?>
                    </h5>
                    <small>Empréstimos Ativos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5>
                        <?php 
                        $devolvidos = 0;
                        foreach ($loans as $loan) {
                            if ($loan['status'] === 'devolvido') $devolvidos++;
                        }
                        echo $devolvidos;
                        ?>
                    </h5>
                    <small>Devolvidos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h5>
                        <?php 
                        $atrasados = 0;
                        $hoje = date('Y-m-d');
                        foreach ($loans as $loan) {
                            if ($loan['status'] === 'ativo' && $loan['data_prevista_devolucao'] < $hoje) {
                                $atrasados++;
                            }
                        }
                        echo $atrasados;
                        ?>
                    </h5>
                    <small>Em Atraso</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5><?php echo count($loans); ?></h5>
                    <small>Total de Empréstimos</small>
                </div>
            </div>
        </div>
    </div>
    
<?php endif; ?>

<?php
// Inclui o rodapé da página
include ROOT_PATH . '/app/views/layout/footer.php';
?>