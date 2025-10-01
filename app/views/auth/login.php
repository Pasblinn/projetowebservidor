<?php
/**
 * View de Login - Formulário de autenticação
 * 
 * Exibe o formulário de login com validações no lado servidor
 * Mantém os dados preenchidos em caso de erro
 */

// Define título da página
$title = 'Login';

// Inclui o header
require_once ROOT_PATH . '/app/views/layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        
        <!-- Card do formulário de login -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">
                    📚 Login no Sistema
                </h4>
            </div>
            
            <div class="card-body">
                
                <!-- Formulário de login -->
                <form action="<?php echo BASE_URL; ?>auth/login" method="POST">
                    
                    <!-- Campo Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <strong>Nome de Usuário:</strong>
                        </label>
                        <input 
                            type="text" 
                            class="form-control<?php echo (isset($_SESSION['errors'])) ? ' is-invalid' : ''; ?>" 
                            id="username" 
                            name="username" 
                            value="<?php echo isset($_SESSION['old_input']['username']) ? htmlspecialchars($_SESSION['old_input']['username']) : ''; ?>"
                            placeholder="Digite seu nome de usuário"
                            required
                            maxlength="<?php echo MAX_USERNAME_LENGTH; ?>"
                        >
                        <div class="form-text">
                            Use 'admin' ou 'bibliotecario' para testar
                        </div>
                    </div>
                    
                    <!-- Campo Senha -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <strong>Senha:</strong>
                        </label>
                        <input 
                            type="password" 
                            class="form-control<?php echo (isset($_SESSION['errors'])) ? ' is-invalid' : ''; ?>" 
                            id="password" 
                            name="password" 
                            placeholder="Digite sua senha"
                            required
                            minlength="<?php echo MIN_PASSWORD_LENGTH; ?>"
                        >
                        <div class="form-text">
                            Use 'password' como senha padrão
                        </div>
                    </div>
                    
                    <!-- Botão de submit -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            🔐 Entrar
                        </button>
                    </div>
                    
                </form>
                
                <!-- Informações adicionais -->
                <hr>
                <div class="text-center text-muted">
                    <small>
                        <strong>Usuários de Teste:</strong><br>
                        Admin: admin / password<br>
                        Bibliotecário: bibliotecario / password
                    </small>
                </div>
                
            </div>
        </div>
        
    </div>
</div>

<?php
/**
 * Remove dados antigos da sessão após usar
 * Evita que dados antigos apareçam em novas tentativas de login
 */
if (isset($_SESSION['old_input'])) {
    unset($_SESSION['old_input']);
}

// Inclui o footer
require_once ROOT_PATH . '/app/views/layout/footer.php';
?>