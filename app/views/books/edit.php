<?php
/**
 * View para editar um livro existente
 * Esta página mostra o formulário preenchido para editar um livro
 */

// Define o título da página
$title = 'Editar Livro';

// Suporta tanto Entity objects quanto arrays
if (is_object($book)) {
    $bookData = $book->toArray();
} else {
    $bookData = $book;
}

// Inclui o cabeçalho da página
include dirname(__DIR__) . '/layout/header.php';
?>

<!-- Título da página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📝 Editar Livro</h2>
    <!-- Link para voltar à lista -->
    <a href="<?php echo base_url('books'); ?>" class="btn btn-secondary">
        ⬅️ Voltar para Lista
    </a>
</div>

<!-- Card com o formulário -->
<div class="card">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">Dados do Livro #<?php echo $bookData['id']; ?></h5>
    </div>
    <div class="card-body">

        <!-- Formulário de edição - envia via POST para books/update -->
        <form action="<?php echo base_url('books/update'); ?>" method="POST">

            <!-- Campo hidden com ID do livro -->
            <input type="hidden" name="id" value="<?php echo $bookData['id']; ?>">

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
                        value="<?php echo escape(old('titulo', $bookData['titulo'])); ?>"
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
                        value="<?php echo escape(old('autor', $bookData['autor'])); ?>"
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
                        value="<?php echo escape(old('isbn', $bookData['isbn'])); ?>"
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
                        value="<?php echo escape(old('editora', $bookData['editora'])); ?>"
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
                        value="<?php echo old('ano_publicacao', $bookData['ano_publicacao']); ?>"
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
                        <?php
                        $categorias = [
                            'Literatura Brasileira',
                            'Literatura Estrangeira',
                            'Ficção Científica',
                            'Romance',
                            'Fantasia',
                            'Técnico',
                            'História',
                            'Biografia'
                        ];
                        $selectedCategoria = old('categoria', $bookData['categoria']);
                        ?>
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo $cat; ?>" <?php echo ($selectedCategoria == $cat) ? 'selected' : ''; ?>>
                                <?php echo $cat; ?>
                            </option>
                        <?php endforeach; ?>
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
                        value="<?php echo old('quantidade_total', $bookData['quantidade_total']); ?>"
                        placeholder="Quantidade de exemplares"
                        required
                        min="1"
                        max="100"
                    >
                    <div class="form-text">Disponível: <?php echo $bookData['quantidade_disponivel']; ?></div>
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
                    value="<?php echo escape(old('localizacao', $bookData['localizacao'])); ?>"
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
                    <button type="submit" class="btn btn-warning">
                        💾 Atualizar Livro
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
// Remove os dados antigos da sessão
if (isset($_SESSION['old_input'])) {
    unset($_SESSION['old_input']);
}

// Inclui o rodapé da página
include dirname(__DIR__) . '/layout/footer.php';
?>
