<?php
/**
 * Model User - Gerencia operações relacionadas aos usuários
 * 
 * Esta classe é responsável por validar, autenticar e gerenciar
 * usuários do sistema usando a classe Database para persistência
 */

class User {
    
    /**
     * Autentica um usuário no sistema
     * @param string $username Nome de usuário
     * @param string $password Senha em texto plano
     * @return array|false Dados do usuário se autenticado, false se falhou
     */
    public static function authenticate($username, $password) {
        // Busca usuário pelo nome de usuário
        $users = Database::findBy('users', ['username' => $username]);
        
        if (empty($users)) {
            return false; // Usuário não encontrado
        }
        
        $user = $users[0]; // Pega o primeiro (e único) usuário encontrado
        
        // Verifica se o usuário está ativo
        if (!$user['ativo']) {
            return false; // Usuário inativo
        }
        
        // Verifica a senha usando password_verify para senhas criptografadas
        if (password_verify($password, $user['password'])) {
            // Remove a senha dos dados retornados por segurança
            unset($user['password']);
            return $user;
        }
        
        return false; // Senha incorreta
    }
    
    /**
     * Cria um novo usuário no sistema
     * @param array $data Dados do usuário
     * @return int|false ID do usuário criado ou false se erro
     */
    public static function create($data) {
        // Valida os dados antes de inserir
        $validation = self::validate($data);
        if ($validation !== true) {
            return false;
        }
        
        // Criptografa a senha antes de salvar
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Define dados padrão
        $data['ativo'] = true;
        $data['data_criacao'] = date('Y-m-d');
        
        // Insere no "banco de dados"
        return Database::insert('users', $data);
    }
    
    /**
     * Atualiza dados de um usuário
     * @param int $id ID do usuário
     * @param array $data Novos dados
     * @return bool True se atualizou com sucesso
     */
    public static function update($id, $data) {
        // Se estiver alterando a senha, criptografa
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            // Remove password do array se estiver vazio para não alterar
            unset($data['password']);
        }
        
        return Database::update('users', $id, $data);
    }
    
    /**
     * Busca usuário por ID
     * @param int $id ID do usuário
     * @return array|null Dados do usuário ou null se não encontrado
     */
    public static function findById($id) {
        $user = Database::getById('users', $id);
        if ($user) {
            // Remove a senha dos dados retornados por segurança
            unset($user['password']);
        }
        return $user;
    }
    
    /**
     * Busca usuário por nome de usuário
     * @param string $username Nome de usuário
     * @return array|null Dados do usuário ou null se não encontrado
     */
    public static function findByUsername($username) {
        $users = Database::findBy('users', ['username' => $username]);
        if (!empty($users)) {
            $user = $users[0];
            unset($user['password']); // Remove senha por segurança
            return $user;
        }
        return null;
    }
    
    /**
     * Obtém todos os usuários do sistema
     * @return array Lista de usuários
     */
    public static function getAll() {
        $users = Database::getAll('users');
        // Remove senhas de todos os usuários por segurança
        foreach ($users as &$user) {
            unset($user['password']);
        }
        return $users;
    }
    
    /**
     * Valida dados de um usuário
     * @param array $data Dados a serem validados
     * @param bool $isUpdate Se é uma atualização (alguns campos opcionais)
     * @return true|array True se válido, array com erros se inválido
     */
    public static function validate($data, $isUpdate = false) {
        $errors = [];
        
        // Validação do nome de usuário
        if (!$isUpdate || isset($data['username'])) {
            if (empty($data['username'])) {
                $errors[] = "Nome de usuário é obrigatório";
            } elseif (strlen($data['username']) > MAX_USERNAME_LENGTH) {
                $errors[] = "Nome de usuário não pode ter mais que " . MAX_USERNAME_LENGTH . " caracteres";
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
                $errors[] = "Nome de usuário pode conter apenas letras, números e underscore";
            } else {
                // Verifica se o username já existe (apenas na criação)
                if (!$isUpdate) {
                    $existing = Database::findBy('users', ['username' => $data['username']]);
                    if (!empty($existing)) {
                        $errors[] = "Nome de usuário já existe";
                    }
                }
            }
        }
        
        // Validação da senha
        if (!$isUpdate || (!empty($data['password']))) {
            if (empty($data['password'])) {
                $errors[] = "Senha é obrigatória";
            } elseif (strlen($data['password']) < MIN_PASSWORD_LENGTH) {
                $errors[] = "Senha deve ter pelo menos " . MIN_PASSWORD_LENGTH . " caracteres";
            }
        }
        
        // Validação do email
        if (!$isUpdate || isset($data['email'])) {
            if (empty($data['email'])) {
                $errors[] = "Email é obrigatório";
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email inválido";
            } else {
                // Verifica se o email já existe (apenas na criação)
                if (!$isUpdate) {
                    $existing = Database::findBy('users', ['email' => $data['email']]);
                    if (!empty($existing)) {
                        $errors[] = "Email já cadastrado";
                    }
                }
            }
        }
        
        // Validação do nome
        if (!$isUpdate || isset($data['nome'])) {
            if (empty($data['nome'])) {
                $errors[] = "Nome é obrigatório";
            } elseif (strlen($data['nome']) < 2) {
                $errors[] = "Nome deve ter pelo menos 2 caracteres";
            }
        }
        
        // Validação do tipo de usuário
        if (!$isUpdate || isset($data['tipo'])) {
            $tipos_validos = ['admin', 'bibliotecario'];
            if (empty($data['tipo']) || !in_array($data['tipo'], $tipos_validos)) {
                $errors[] = "Tipo de usuário inválido";
            }
        }
        
        // Retorna true se não há erros, senão retorna array de erros
        return empty($errors) ? true : $errors;
    }
}
?>