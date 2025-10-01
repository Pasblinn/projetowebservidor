<?php
/**
 * View para listar todos os livros
 * Esta página mostra uma tabela com todos os livros cadastrados
 */

// Define o título da página
$title = 'Lista de Livros';

// Inclui o cabeçalho da página
include ROOT_PATH . '/app/views/layout/header.php';
?>

<!-- Título da página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📚 Lista de Livros</h2>
    <!-- Botão para adicionar novo livro -->
    <a href="<?php echo BASE_URL; ?>books/create" class="btn btn-success">
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
                foreach ($books as $book): 
                ?>
                    <tr>
                        <!-- Mostra o ID do livro -->
                        <td><?php echo $book['id']; ?></td>
                        
                        <!-- Mostra o título (escapa HTML para segurança) -->
                        <td><?php echo htmlspecialchars($book['titulo']); ?></td>
                        
                        <!-- Mostra o autor -->
                        <td><?php echo htmlspecialchars($book['autor']); ?></td>
                        
                        <!-- Mostra o ISBN -->
                        <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                        
                        <!-- Mostra a editora -->
                        <td><?php echo htmlspecialchars($book['editora']); ?></td>
                        
                        <!-- Mostra o ano de publicação -->
                        <td><?php echo $book['ano_publicacao']; ?></td>
                        
                        <!-- Mostra a categoria -->
                        <td>
                            <span class="badge bg-secondary">
                                <?php echo htmlspecialchars($book['categoria']); ?>
                            </span>
                        </td>
                        
                        <!-- Mostra quantidade total -->
                        <td><?php echo $book['quantidade_total']; ?></td>
                        
                        <!-- Mostra quantidade disponível com cor baseada na disponibilidade -->
                        <td>
                            <?php if ($book['quantidade_disponivel'] > 0): ?>
                                <span class="badge bg-success"><?php echo $book['quantidade_disponivel']; ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger">0</span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- Mostra a localização -->
                        <td><?php echo htmlspecialchars($book['localizacao']); ?></td>
                        
                        <!-- Botões de ação (editar e excluir) -->
                        <td>
                            <!-- Link para editar o livro -->
                            <a href="<?php echo BASE_URL; ?>books/edit?id=<?php echo $book['id']; ?>" 
                               class="btn btn-sm btn-warning">
                                ✏️ Editar
                            </a>
                            
                            <!-- Link para excluir o livro (com confirmação JavaScript) -->
                            <a href="<?php echo BASE_URL; ?>books/delete?id=<?php echo $book['id']; ?>" 
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
include ROOT_PATH . '/app/views/layout/footer.php';
?>