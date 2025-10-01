<?php
/**
 * View para listar todos os membros da biblioteca
 * Esta página mostra uma tabela com todos os membros cadastrados
 */

// Define o título da página
$title = 'Lista de Membros';

// Inclui o cabeçalho da página
include ROOT_PATH . '/app/views/layout/header.php';
?>

<!-- Título da página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>👥 Lista de Membros</h2>
    <!-- Botão para adicionar novo membro -->
    <a href="<?php echo BASE_URL; ?>members/create" class="btn btn-success">
        ➕ Novo Membro
    </a>
</div>

<!-- Verifica se existem membros para mostrar -->
<?php if (empty($members)): ?>
    <!-- Se não há membros, mostra uma mensagem -->
    <div class="alert alert-info">
        <h4>Nenhum membro cadastrado</h4>
        <p>Clique no botão "Novo Membro" para adicionar o primeiro membro ao sistema.</p>
    </div>
<?php else: ?>
    <!-- Se há membros, mostra a tabela -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <!-- Cabeçalho da tabela -->
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th>Data Nascimento</th>
                    <th>Categoria</th>
                    <th>Status</th>
                    <th>Data Cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <!-- Corpo da tabela -->
            <tbody>
                <?php 
                // Loop para percorrer todos os membros e mostrar cada um em uma linha
                foreach ($members as $member): 
                ?>
                    <tr>
                        <!-- Mostra o ID do membro -->
                        <td><?php echo $member['id']; ?></td>
                        
                        <!-- Mostra o nome (escapa HTML para segurança) -->
                        <td><?php echo htmlspecialchars($member['nome']); ?></td>
                        
                        <!-- Mostra o email -->
                        <td><?php echo htmlspecialchars($member['email']); ?></td>
                        
                        <!-- Mostra o telefone -->
                        <td><?php echo htmlspecialchars($member['telefone']); ?></td>
                        
                        <!-- Mostra o CPF -->
                        <td><?php echo htmlspecialchars($member['cpf']); ?></td>
                        
                        <!-- Mostra a data de nascimento formatada -->
                        <td><?php echo date('d/m/Y', strtotime($member['data_nascimento'])); ?></td>
                        
                        <!-- Mostra a categoria com badge colorido -->
                        <td>
                            <?php
                            // Define cores diferentes para cada categoria
                            $badge_color = 'secondary';
                            if ($member['categoria'] == 'estudante') $badge_color = 'primary';
                            elseif ($member['categoria'] == 'professor') $badge_color = 'success';
                            elseif ($member['categoria'] == 'funcionario') $badge_color = 'info';
                            ?>
                            <span class="badge bg-<?php echo $badge_color; ?>">
                                <?php echo ucfirst($member['categoria']); ?>
                            </span>
                        </td>
                        
                        <!-- Mostra o status (ativo/inativo) -->
                        <td>
                            <?php if ($member['ativo']): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inativo</span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- Mostra a data de cadastro -->
                        <td><?php echo date('d/m/Y', strtotime($member['data_cadastro'])); ?></td>
                        
                        <!-- Botões de ação (editar e excluir) -->
                        <td>
                            <!-- Link para editar o membro -->
                            <a href="<?php echo BASE_URL; ?>members/edit?id=<?php echo $member['id']; ?>" 
                               class="btn btn-sm btn-warning">
                                ✏️ Editar
                            </a>
                            
                            <!-- Link para excluir o membro (com confirmação JavaScript) -->
                            <a href="<?php echo BASE_URL; ?>members/delete?id=<?php echo $member['id']; ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Tem certeza que deseja excluir este membro?')">
                                🗑️ Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Informações sobre o total de membros -->
    <div class="mt-3">
        <small class="text-muted">
            Total de membros cadastrados: <strong><?php echo count($members); ?></strong>
        </small>
    </div>
<?php endif; ?>

<?php
// Inclui o rodapé da página
include ROOT_PATH . '/app/views/layout/footer.php';
?>