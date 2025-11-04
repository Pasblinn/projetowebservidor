<?php
/**
 * View para criar um novo livro
 * Esta página mostra o formulário para cadastrar um livro no sistema
 */

// Define o título da página
$title = 'Cadastrar Livro';

// Inclui o cabeçalho da página
include dirname(__DIR__) . '/layout/header.php';
?>

<!-- Título da página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📚 Cadastrar Novo Livro</h2>
    <!-- Link para voltar à lista -->
    <a href="<?php echo base_url('books'); ?>" class="btn btn-secondary">
        ⬅️ Voltar para Lista
    </a>
</div>

<!-- Card com o formulário -->
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">Dados do Livro</h5>
    </div>
    <div class="card-body">

        <!-- Formulário de cadastro - envia via POST para books/store -->
        <form action="<?php echo base_url('books/store'); ?>" method="POST">

            <div class="row">
                <!-- Campo Título -->
                <div class="col-md-8 mb-3">
                    <label for="titulo" class="form-label">
                        <strong>Título do Livro:</strong> <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="titulo"
                        name="titulo"
                        value="<?php echo escape(old('titulo')); ?>"
                        placeholder="Digite o título do livro"
                        required
                        maxlength="200"
                    >
                </div>

                <!-- Campo Autor -->
                <div class="col-md-4 mb-3">
                    <label for="autor" class="form-label">
                        <strong>Autor:</strong> <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="autor"
                        name="autor"
                        value="<?php echo escape(old('autor')); ?>"
                        placeholder="Nome do autor"
                        required
                        maxlength="150"
                    >
                </div>
            </div>

            <div class="row">
                <!-- Campo ISBN -->
                <div class="col-md-6 mb-3">
                    <label for="isbn" class="form-label">
                        <strong>ISBN:</strong> <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="isbn"
                        name="isbn"
                        value="<?php echo escape(old('isbn')); ?>"
                        placeholder="Ex: 9788525406626"
                        required
                        maxlength="20"
                    >
                    <div class="form-text">Digite apenas números (10 ou 13 dígitos)</div>
                </div>

                <!-- Campo Editora -->
                <div class="col-md-6 mb-3">
                    <label for="editora" class="form-label">
                        <strong>Editora:</strong> <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="editora"
                        name="editora"
                        value="<?php echo escape(old('editora')); ?>"
                        placeholder="Nome da editora"
                        required
                        maxlength="100"
                    >
                </div>
            </div>

            <div class="row">
                <!-- Campo Ano de Publicação -->
                <div class="col-md-4 mb-3">
                    <label for="ano_publicacao" class="form-label">
                        <strong>Ano de Publicação:</strong> <span class="text-danger">*</span>
                    </label>
                    <input
                        type="number"
                        class="form-control"
                        id="ano_publicacao"
                        name="ano_publicacao"
                        value="<?php echo old('ano_publicacao'); ?>"
                        placeholder="Ex: 2024"
                        required
                        min="1450"
                        max="<?php echo date('Y') + 1; ?>"
                    >
                </div>

                <!-- Campo Categoria -->
                <div class="col-md-4 mb-3">
                    <label for="categoria" class="form-label">
                        <strong>Categoria:</strong> <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" id="categoria" name="categoria" required>
                        <option value="">Selecione uma categoria</option>
                        <option value="Literatura Brasileira" <?php echo (old('categoria') == 'Literatura Brasileira') ? 'selected' : ''; ?>>
                            Literatura Brasileira
                        </option>
                        <option value="Literatura Estrangeira" <?php echo (old('categoria') == 'Literatura Estrangeira') ? 'selected' : ''; ?>>
                            Literatura Estrangeira
                        </option>
                        <option value="Ficção Científica" <?php echo (old('categoria') == 'Ficção Científica') ? 'selected' : ''; ?>>
                            Ficção Científica
                        </option>
                        <option value="Romance" <?php echo (old('categoria') == 'Romance') ? 'selected' : ''; ?>>
                            Romance
                        </option>
                        <option value="Fantasia" <?php echo (old('categoria') == 'Fantasia') ? 'selected' : ''; ?>>
                            Fantasia
                        </option>
                        <option value="Técnico" <?php echo (old('categoria') == 'Técnico') ? 'selected' : ''; ?>>
                            Técnico
                        </option>
                        <option value="História" <?php echo (old('categoria') == 'História') ? 'selected' : ''; ?>>
                            História
                        </option>
                        <option value="Biografia" <?php echo (old('categoria') == 'Biografia') ? 'selected' : ''; ?>>
                            Biografia
                        </option>
                    </select>
                </div>

                <!-- Campo Quantidade -->
                <div class="col-md-4 mb-3">
                    <label for="quantidade_total" class="form-label">
                        <strong>Quantidade Total:</strong> <span class="text-danger">*</span>
                    </label>
                    <input
                        type="number"
                        class="form-control"
                        id="quantidade_total"
                        name="quantidade_total"
                        value="<?php echo old('quantidade_total', '1'); ?>"
                        placeholder="Quantidade de exemplares"
                        required
                        min="1"
                        max="100"
                    >
                </div>
            </div>

            <!-- Campo Localização -->
            <div class="mb-3">
                <label for="localizacao" class="form-label">
                    <strong>Localização na Biblioteca:</strong> <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="localizacao"
                    name="localizacao"
                    value="<?php echo escape(old('localizacao')); ?>"
                    placeholder="Ex: A-001, Estante 5, etc."
                    required
                    maxlength="50"
                >
                <div class="form-text">Informe onde o livro está localizado fisicamente</div>
            </div>

            <!-- Botões -->
            <div class="row">
                <div class="col-12">
                    <!-- Botão para salvar -->
                    <button type="submit" class="btn btn-success">
                        💾 Cadastrar Livro
                    </button>

                    <!-- Botão para cancelar -->
                    <a href="<?php echo base_url('books'); ?>" class="btn btn-secondary">
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
include dirname(__DIR__) . '/layout/footer.php';
?>
