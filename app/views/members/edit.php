<?php
/**
 * View para editar um membro existente da biblioteca
 * Esta página mostra o formulário de edição com os dados do membro
 *
 * REQUISITO: Orientação a Objetos - usa objeto Member com métodos getters
 */

// Define o título da página
$title = 'Editar Membro';

// Inclui o cabeçalho da página
include ROOT_PATH . '/app/views/layout/header.php';
?>

<!-- Título da página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>✏️ Editar Membro</h2>
    <!-- Link para voltar à lista -->
    <a href="<?php echo base_url('members'); ?> " class="btn btn-secondary">
        ⬅️ Voltar para Lista
    </a>
</div>

<!-- Card com o formulário -->
<div class="card">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">Dados do Membro</h5>
    </div>
    <div class="card-body">

        <!-- Formulário de edição - envia via POST para members/update -->
        <form action="<?php echo base_url('members/update'); ?>" method="POST">

            <!-- Campo oculto com o ID do membro -->
            <input type="hidden" name="id" value="<?php echo $member->getId(); ?>">

            <div class="row">
                <!-- Campo Nome -->
                <div class="col-md-8 mb-3">
                    <label for="nome" class="form-label">
                        <strong>Nome Completo:</strong> <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="nome"
                        name="nome"
                        value="<?php echo isset($_SESSION['old_input']['nome']) ? htmlspecialchars($_SESSION['old_input']['nome']) : htmlspecialchars($member->getNome()); ?>"
                        placeholder="Digite o nome completo"
                        required
                        maxlength="100"
                    >
                </div>

                <!-- Campo CPF -->
                <div class="col-md-4 mb-3">
                    <label for="cpf" class="form-label">
                        <strong>CPF:</strong> <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="cpf"
                        name="cpf"
                        value="<?php echo isset($_SESSION['old_input']['cpf']) ? htmlspecialchars($_SESSION['old_input']['cpf']) : htmlspecialchars($member->getCpf()); ?>"
                        placeholder="000.000.000-00"
                        required
                        maxlength="14"
                    >
                </div>
            </div>

            <div class="row">
                <!-- Campo Email -->
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">
                        <strong>Email:</strong> <span class="text-danger">*</span>
                    </label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="<?php echo isset($_SESSION['old_input']['email']) ? htmlspecialchars($_SESSION['old_input']['email']) : htmlspecialchars($member->getEmail()); ?>"
                        placeholder="email@exemplo.com"
                        required
                        maxlength="100"
                    >
                </div>

                <!-- Campo Telefone -->
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">
                        <strong>Telefone:</strong> <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="telefone"
                        name="telefone"
                        value="<?php echo isset($_SESSION['old_input']['telefone']) ? htmlspecialchars($_SESSION['old_input']['telefone']) : htmlspecialchars($member->getTelefone()); ?>"
                        placeholder="(11) 99999-9999"
                        required
                        maxlength="20"
                    >
                </div>
            </div>

            <!-- Campo Endereço -->
            <div class="mb-3">
                <label for="endereco" class="form-label">
                    <strong>Endereço Completo:</strong> <span class="text-danger">*</span>
                </label>
                <textarea
                    class="form-control"
                    id="endereco"
                    name="endereco"
                    rows="2"
                    placeholder="Rua, número, bairro, cidade, CEP"
                    required
                    maxlength="255"
                ><?php echo isset($_SESSION['old_input']['endereco']) ? htmlspecialchars($_SESSION['old_input']['endereco']) : htmlspecialchars($member->getEndereco()); ?></textarea>
            </div>

            <div class="row">
                <!-- Campo Data de Nascimento -->
                <div class="col-md-4 mb-3">
                    <label for="data_nascimento" class="form-label">
                        <strong>Data de Nascimento:</strong> <span class="text-danger">*</span>
                    </label>
                    <input
                        type="date"
                        class="form-control"
                        id="data_nascimento"
                        name="data_nascimento"
                        value="<?php echo isset($_SESSION['old_input']['data_nascimento']) ? $_SESSION['old_input']['data_nascimento'] : $member->getDataNascimento()->format('Y-m-d'); ?>"
                        required
                        max="<?php echo date('Y-m-d', strtotime('-13 years')); ?>"
                    >
                </div>

                <!-- Campo Categoria -->
                <div class="col-md-8 mb-3">
                    <label for="categoria" class="form-label">
                        <strong>Categoria:</strong> <span class="text-danger">*</span>
                    </label>
                    <?php $categoriaAtual = isset($_SESSION['old_input']['categoria']) ? $_SESSION['old_input']['categoria'] : $member->getCategoria(); ?>
                    <select class="form-control" id="categoria" name="categoria" required>
                        <option value="">Selecione a categoria</option>
                        <option value="estudante" <?php echo $categoriaAtual == 'estudante' ? 'selected' : ''; ?>>
                            👨‍🎓 Estudante
                        </option>
                        <option value="professor" <?php echo $categoriaAtual == 'professor' ? 'selected' : ''; ?>>
                            👨‍🏫 Professor
                        </option>
                        <option value="comunidade" <?php echo $categoriaAtual == 'comunidade' ? 'selected' : ''; ?>>
                            👥 Comunidade Externa
                        </option>
                    </select>
                </div>
            </div>

            <!-- Botões -->
            <div class="row">
                <div class="col-12">
                    <!-- Botão para salvar -->
                    <button type="submit" class="btn btn-warning">
                        💾 Atualizar Membro
                    </button>

                    <!-- Botão para cancelar -->
                    <a href="<?php echo base_url('members'); ?> " class="btn btn-secondary">
                        ❌ Cancelar
                    </a>
                </div>
            </div>

        </form>

    </div>
</div>

<!-- Informação sobre campos obrigatórios -->
<div class="mt-3">
    <small class="text-muted">
        <span class="text-danger">*</span> Campos obrigatórios
    </small>
</div>

<?php
// Remove os dados antigos da sessão para não aparecer em outros formulários
if (isset($_SESSION['old_input'])) {
    unset($_SESSION['old_input']);
}

// Inclui o rodapé da página
include ROOT_PATH . '/app/views/layout/footer.php';
?>
