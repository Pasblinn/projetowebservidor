<?php
/**
 * View para listar todos os membros da biblioteca
 * Esta página mostra uma tabela com todos os membros cadastrados
 *
 * REQUISITO: Orientação a Objetos - usa métodos getters dos objetos Member
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
    <a href="<?php echo base_url('members/create'); ?>" class="btn btn-success">
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
                // ORIENTAÇÃO A OBJETOS: $member é um objeto da classe Member
                foreach ($members as $member):
                ?>
                    <tr>
                        <!-- Mostra o ID do membro usando método getter -->
                        <td><?php echo $member->getId(); ?></td>

                        <!-- Mostra o nome usando método getter (escapa HTML para segurança) -->
                        <td><?php echo htmlspecialchars($member->getNome()); ?></td>

                        <!-- Mostra o email usando método getter -->
                        <td><?php echo htmlspecialchars($member->getEmail()); ?></td>

                        <!-- Mostra o telefone usando método getter -->
                        <td><?php echo htmlspecialchars($member->getTelefone()); ?></td>

                        <!-- Mostra o CPF usando método getter -->
                        <td><?php echo htmlspecialchars($member->getCpf()); ?></td>

                        <!-- Mostra a data de nascimento formatada usando objeto DateTime -->
                        <td><?php echo $member->getDataNascimento()->format('d/m/Y'); ?></td>

                        <!-- Mostra a categoria com badge colorido -->
                        <td>
                            <?php
                            // Define cores diferentes para cada categoria
                            $badge_color = 'secondary';
                            if ($member->getCategoria() == 'estudante') $badge_color = 'primary';
                            elseif ($member->getCategoria() == 'professor') $badge_color = 'success';
                            elseif ($member->getCategoria() == 'comunidade') $badge_color = 'info';
                            ?>
                            <span class="badge bg-<?php echo $badge_color; ?>">
                                <?php echo ucfirst($member->getCategoria()); ?>
                            </span>
                        </td>

                        <!-- Mostra o status (ativo/inativo) usando método isAtivo() -->
                        <td>
                            <?php if ($member->isAtivo()): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inativo</span>
                            <?php endif; ?>
                        </td>

                        <!-- Mostra a data de cadastro (pode ser null) -->
                        <td><?php echo $member->getDataCadastro() ? $member->getDataCadastro()->format('d/m/Y') : 'N/A'; ?></td>

                        <!-- Botões de ação (editar e excluir) -->
                        <td>
                            <!-- Link para editar o membro -->
                            <a href="<?php echo base_url('members/edit?id=' . $member->getId()); ?>"
                               class="btn btn-sm btn-warning">
                                ✏️ Editar
                            </a>

                            <!-- Link para excluir o membro (com confirmação JavaScript) -->
                            <a href="<?php echo base_url('members/delete?id=' . $member->getId()); ?>"
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
