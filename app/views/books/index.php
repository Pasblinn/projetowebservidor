<?php
/**
 * View para listar todos os livros
 * Esta página mostra uma tabela com todos os livros cadastrados
 *
 * REQUISITO: Orientação a Objetos - usa métodos getters dos objetos Book
 */

// Define o título da página
$title = 'Lista de Livros';

// Inclui o cabeçalho da página
include dirname(__DIR__) . '/layout/header.php';
?>

<!-- Título da página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📚 Lista de Livros</h2>
    <!-- Botão para adicionar novo livro -->
    <a href="<?php echo base_url('books/create'); ?>" class="btn btn-success">
        ➕ Novo Livro
    </a>
</div>

<!-- Verifica se existem livros para mostrar -->
<?php if (empty($books)): ?>
    <!-- Se não há livros, mostra uma mensagem -->
    <div class="alert alert-info">
        <h4>Nenhum livro cadastrado</h4>
        <p>Clique no botão "Novo Livro" para adicionar o primeiro livro ao sistema.</p>
    </div>
<?php else: ?>
    <!-- Se há livros, mostra a tabela -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <!-- Cabeçalho da tabela -->
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>ISBN</th>
                    <th>Editora</th>
                    <th>Ano</th>
                    <th>Categoria</th>
                    <th>Quantidade</th>
                    <th>Disponível</th>
                    <th>Localização</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <!-- Corpo da tabela -->
            <tbody>
                <?php
                // Loop para percorrer todos os livros e mostrar cada um em uma linha
                // ORIENTAÇÃO A OBJETOS: $book é um objeto da classe Book
                foreach ($books as $book):
                ?>
                    <tr>
                        <!-- Mostra o ID do livro usando método getter -->
                        <td><?php echo $book->getId(); ?></td>

                        <!-- Mostra o título usando método getter (escapa HTML para segurança) -->
                        <td><?php echo escape($book->getTitulo()); ?></td>

                        <!-- Mostra o autor usando método getter -->
                        <td><?php echo escape($book->getAutor()); ?></td>

                        <!-- Mostra o ISBN usando método getter -->
                        <td><?php echo escape($book->getIsbn()); ?></td>

                        <!-- Mostra a editora usando método getter -->
                        <td><?php echo escape($book->getEditora()); ?></td>

                        <!-- Mostra o ano de publicação usando método getter -->
                        <td><?php echo $book->getAnoPublicacao(); ?></td>

                        <!-- Mostra a categoria usando método getter -->
                        <td>
                            <span class="badge bg-secondary">
                                <?php echo escape($book->getCategoria()); ?>
                            </span>
                        </td>

                        <!-- Mostra quantidade total usando método getter -->
                        <td><?php echo $book->getQuantidadeTotal(); ?></td>

                        <!-- Mostra quantidade disponível com cor baseada na disponibilidade -->
                        <td>
                            <?php if ($book->getQuantidadeDisponivel() > 0): ?>
                                <span class="badge bg-success"><?php echo $book->getQuantidadeDisponivel(); ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger">0</span>
                            <?php endif; ?>
                        </td>

                        <!-- Mostra a localização usando método getter -->
                        <td><?php echo escape($book->getLocalizacao()); ?></td>

                        <!-- Botões de ação (editar e excluir) -->
                        <td>
                            <!-- Link para editar o livro -->
                            <a href="<?php echo base_url('books/edit?id=' . $book->getId()); ?>"
                               class="btn btn-sm btn-warning">
                                ✏️ Editar
                            </a>

                            <!-- Link para excluir o livro (com confirmação JavaScript) -->
                            <a href="<?php echo base_url('books/delete?id=' . $book->getId()); ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Tem certeza que deseja excluir este livro?')">
                                🗑️ Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Informações sobre o total de livros -->
    <div class="mt-3">
        <small class="text-muted">
            Total de livros cadastrados: <strong><?php echo count($books); ?></strong>
        </small>
    </div>
<?php endif; ?>

<?php
// Inclui o rodapé da página
include dirname(__DIR__) . '/layout/footer.php';
?>
