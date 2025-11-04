<?php
/**
 * View para criar um novo empréstimo
 * Esta página mostra o formulário para registrar um empréstimo de livro
 *
 * REQUISITO: Orientação a Objetos - usa objetos Member e Book com métodos getters
 */

// Define o título da página
$title = 'Novo Empréstimo';

// Inclui o cabeçalho da página
include ROOT_PATH . '/app/views/layout/header.php';
?>

<!-- Título da página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📖 Novo Empréstimo</h2>
    <!-- Link para voltar à lista -->
    <a href="<?php echo base_url('loans'); ?> " class="btn btn-secondary">
        ⬅️ Voltar para Lista
    </a>
</div>

<!-- Verificar se há livros e membros disponíveis -->
<?php if (empty($available_books)): ?>
    <div class="alert alert-warning">
        <h5>Nenhum livro disponível</h5>
        <p>Não há livros disponíveis para empréstimo no momento.</p>
        <a href="<?php echo base_url('books'); ?> " class="btn btn-primary">Ver Livros</a>
    </div>
<?php elseif (empty($active_members)): ?>
    <div class="alert alert-warning">
        <h5>Nenhum membro ativo</h5>
        <p>Não há membros ativos cadastrados para empréstimo.</p>
        <a href="<?php echo base_url('members'); ?> " class="btn btn-success">Ver Membros</a>
    </div>
<?php else: ?>

    <!-- Card com o formulário -->
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">Dados do Empréstimo</h5>
        </div>
        <div class="card-body">

            <!-- Formulário - envia para loans/store -->
            <form action="<?php echo base_url('loans/store'); ?>" method="POST">

                <div class="row">
                    <!-- Seleção do Membro -->
                    <div class="col-md-6 mb-3">
                        <label for="member_id" class="form-label">
                            <strong>Membro:</strong> <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" id="member_id" name="member_id" required>
                            <option value="">Selecione o membro</option>
                            <?php
                            // ORIENTAÇÃO A OBJETOS: $member é um objeto da classe Member
                            foreach ($active_members as $member):
                            ?>
                                <option value="<?php echo $member->getId(); ?>"
                                    <?php echo (isset($_SESSION['old_input']['member_id']) && $_SESSION['old_input']['member_id'] == $member->getId()) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($member->getNome()); ?>
                                    (<?php echo htmlspecialchars($member->getEmail()); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Selecione qual membro está pegando o livro emprestado</div>
                    </div>

                    <!-- Seleção do Livro -->
                    <div class="col-md-6 mb-3">
                        <label for="book_id" class="form-label">
                            <strong>Livro:</strong> <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" id="book_id" name="book_id" required>
                            <option value="">Selecione o livro</option>
                            <?php
                            // ORIENTAÇÃO A OBJETOS: $book é um objeto da classe Book
                            foreach ($available_books as $book):
                            ?>
                                <option value="<?php echo $book->getId(); ?>"
                                    <?php echo (isset($_SESSION['old_input']['book_id']) && $_SESSION['old_input']['book_id'] == $book->getId()) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($book->getTitulo()); ?> - <?php echo htmlspecialchars($book->getAutor()); ?>
                                    (<?php echo $book->getQuantidadeDisponivel(); ?> disponível)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Selecione qual livro será emprestado</div>
                    </div>
                </div>

                <!-- Data de empréstimo -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="data_emprestimo" class="form-label">
                            <strong>Data do Empréstimo:</strong> <span class="text-danger">*</span>
                        </label>
                        <input
                            type="date"
                            class="form-control"
                            id="data_emprestimo"
                            name="data_emprestimo"
                            value="<?php echo isset($_SESSION['old_input']['data_emprestimo']) ? $_SESSION['old_input']['data_emprestimo'] : date('Y-m-d'); ?>"
                            required
                            max="<?php echo date('Y-m-d'); ?>"
                        >
                        <div class="form-text">Data em que o livro está sendo emprestado</div>
                    </div>

                    <!-- Informação sobre devolução -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <strong>Data Prevista de Devolução:</strong>
                        </label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-info">Será calculada automaticamente (+14 dias)</span>
                        </div>
                        <div class="form-text">O prazo padrão é de 2 semanas</div>
                    </div>
                </div>

                <!-- Campo observações -->
                <div class="mb-3">
                    <label for="observacoes" class="form-label">
                        <strong>Observações:</strong>
                    </label>
                    <textarea
                        class="form-control"
                        id="observacoes"
                        name="observacoes"
                        rows="3"
                        placeholder="Observações sobre este empréstimo (opcional)"
                        maxlength="255"
                    ><?php echo isset($_SESSION['old_input']['observacoes']) ? htmlspecialchars($_SESSION['old_input']['observacoes']) : ''; ?></textarea>
                    <div class="form-text">Campo opcional para anotações</div>
                </div>

                <!-- Botões -->
                <div class="row">
                    <div class="col-12">
                        <!-- Botão para salvar -->
                        <button type="submit" class="btn btn-warning">
                            📖 Registrar Empréstimo
                        </button>

                        <!-- Botão para cancelar -->
                        <a href="<?php echo base_url('loans'); ?> " class="btn btn-secondary">
                            ❌ Cancelar
                        </a>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <!-- Informações importantes -->
    <div class="alert alert-info mt-3">
        <h6>ℹ️ Informações sobre Empréstimos:</h6>
        <ul class="mb-0">
            <li><strong>Prazo:</strong> 14 dias corridos a partir da data do empréstimo</li>
            <li><strong>Renovação:</strong> Deve ser feita antes do vencimento</li>
            <li><strong>Multa:</strong> Aplica-se em caso de atraso na devolução</li>
            <li><strong>Responsabilidade:</strong> O membro é responsável pela integridade do livro</li>
        </ul>
    </div>

<?php endif; ?>

<!-- Informação sobre campos obrigatórios -->
<div class="mt-3">
    <small class="text-muted">
        <span class="text-danger">*</span> Campos obrigatórios
    </small>
</div>

<?php
// Remove os dados antigos da sessão
if (isset($_SESSION['old_input'])) {
    unset($_SESSION['old_input']);
}

// Inclui o rodapé da página
include ROOT_PATH . '/app/views/layout/footer.php';
?>
