<?php
/**
 * Classe Database - Simulador de banco de dados usando arrays
 * 
 * Esta classe simula um banco de dados em memória usando arrays
 * para armazenar dados de usuários, livros e empréstimos
 * Em um projeto real, esta classe se conectaria a um banco de dados
 */

class Database {
    // Array que simula a tabela de usuários do sistema
    private static $users = [
        1 => [
            'id' => 1,
            'username' => 'admin',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'email' => 'admin@biblioteca.com',
            'nome' => 'Administrador',
            'tipo' => 'admin',
            'ativo' => true,
            'data_criacao' => '2024-01-01'
        ],
        2 => [
            'id' => 2,
            'username' => 'bibliotecario',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'email' => 'bibliotecario@biblioteca.com',
            'nome' => 'João Silva',
            'tipo' => 'bibliotecario',
            'ativo' => true,
            'data_criacao' => '2024-01-02'
        ]
    ];

    // Array que simula a tabela de livros
    private static $books = [
        1 => [
            'id' => 1,
            'titulo' => 'Dom Casmurro',
            'autor' => 'Machado de Assis',
            'isbn' => '9788525406626',
            'editora' => 'Globo',
            'ano_publicacao' => 1899,
            'categoria' => 'Literatura Brasileira',
            'quantidade_total' => 5,
            'quantidade_disponivel' => 3,
            'localizacao' => 'A-001',
            'data_cadastro' => '2024-01-01'
        ],
        2 => [
            'id' => 2,
            'titulo' => 'O Cortiço',
            'autor' => 'Aluísio Azevedo',
            'isbn' => '9788525406633',
            'editora' => 'Ática',
            'ano_publicacao' => 1890,
            'categoria' => 'Literatura Brasileira',
            'quantidade_total' => 3,
            'quantidade_disponivel' => 2,
            'localizacao' => 'A-002',
            'data_cadastro' => '2024-01-02'
        ]
    ];

    // Array que simula a tabela de membros da biblioteca
    private static $members = [
        1 => [
            'id' => 1,
            'nome' => 'Maria Santos',
            'email' => 'maria@email.com',
            'telefone' => '(11) 99999-9999',
            'endereco' => 'Rua das Flores, 123',
            'cpf' => '123.456.789-00',
            'data_nascimento' => '1990-05-15',
            'categoria' => 'estudante',
            'ativo' => true,
            'data_cadastro' => '2024-01-10'
        ],
        2 => [
            'id' => 2,
            'nome' => 'Pedro Oliveira',
            'email' => 'pedro@email.com',
            'telefone' => '(11) 88888-8888',
            'endereco' => 'Av. Principal, 456',
            'cpf' => '987.654.321-00',
            'data_nascimento' => '1985-12-20',
            'categoria' => 'professor',
            'ativo' => true,
            'data_cadastro' => '2024-01-11'
        ]
    ];

    // Array que simula a tabela de empréstimos
    private static $loans = [
        1 => [
            'id' => 1,
            'member_id' => 1,
            'book_id' => 1,
            'data_emprestimo' => '2024-01-15',
            'data_prevista_devolucao' => '2024-01-29',
            'data_devolucao' => null,
            'status' => 'ativo',
            'observacoes' => '',
            'usuario_responsavel' => 'bibliotecario'
        ]
    ];

    // Contadores para IDs auto-increment
    private static $next_user_id = 3;
    private static $next_book_id = 3;
    private static $next_member_id = 3;
    private static $next_loan_id = 2;

    /**
     * Obtém todos os registros de uma tabela específica
     * @param string $table Nome da tabela (users, books, members, loans)
     * @return array Array com todos os registros da tabela
     */
    public static function getAll($table) {
        switch ($table) {
            case 'users':
                return self::$users;
            case 'books':
                return self::$books;
            case 'members':
                return self::$members;
            case 'loans':
                return self::$loans;
            default:
                return [];
        }
    }

    /**
     * Obtém um registro específico pelo ID
     * @param string $table Nome da tabela
     * @param int $id ID do registro
     * @return array|null Registro encontrado ou null se não existir
     */
    public static function getById($table, $id) {
        $data = self::getAll($table);
        return isset($data[$id]) ? $data[$id] : null;
    }

    /**
     * Insere um novo registro na tabela
     * @param string $table Nome da tabela
     * @param array $data Dados a serem inseridos
     * @return int ID do registro inserido
     */
    public static function insert($table, $data) {
        $id = self::getNextId($table);
        $data['id'] = $id;
        
        switch ($table) {
            case 'users':
                self::$users[$id] = $data;
                break;
            case 'books':
                self::$books[$id] = $data;
                break;
            case 'members':
                self::$members[$id] = $data;
                break;
            case 'loans':
                self::$loans[$id] = $data;
                break;
        }
        
        return $id;
    }

    /**
     * Atualiza um registro existente
     * @param string $table Nome da tabela
     * @param int $id ID do registro a ser atualizado
     * @param array $data Novos dados
     * @return bool True se atualizou com sucesso
     */
    public static function update($table, $id, $data) {
        if (self::getById($table, $id) === null) {
            return false;
        }
        
        $data['id'] = $id; // Garante que o ID não seja alterado
        
        switch ($table) {
            case 'users':
                self::$users[$id] = array_merge(self::$users[$id], $data);
                break;
            case 'books':
                self::$books[$id] = array_merge(self::$books[$id], $data);
                break;
            case 'members':
                self::$members[$id] = array_merge(self::$members[$id], $data);
                break;
            case 'loans':
                self::$loans[$id] = array_merge(self::$loans[$id], $data);
                break;
            default:
                return false;
        }
        
        return true;
    }

    /**
     * Remove um registro da tabela
     * @param string $table Nome da tabela
     * @param int $id ID do registro a ser removido
     * @return bool True se removeu com sucesso
     */
    public static function delete($table, $id) {
        if (self::getById($table, $id) === null) {
            return false;
        }
        
        switch ($table) {
            case 'users':
                unset(self::$users[$id]);
                break;
            case 'books':
                unset(self::$books[$id]);
                break;
            case 'members':
                unset(self::$members[$id]);
                break;
            case 'loans':
                unset(self::$loans[$id]);
                break;
            default:
                return false;
        }
        
        return true;
    }

    /**
     * Busca registros com base em critérios
     * @param string $table Nome da tabela
     * @param array $criteria Critérios de busca (campo => valor)
     * @return array Array com registros encontrados
     */
    public static function findBy($table, $criteria) {
        $all_data = self::getAll($table);
        $results = [];
        
        foreach ($all_data as $record) {
            $match = true;
            foreach ($criteria as $field => $value) {
                if (!isset($record[$field]) || $record[$field] !== $value) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                $results[] = $record;
            }
        }
        
        return $results;
    }

    /**
     * Obtém o próximo ID disponível para uma tabela
     * @param string $table Nome da tabela
     * @return int Próximo ID disponível
     */
    private static function getNextId($table) {
        switch ($table) {
            case 'users':
                return self::$next_user_id++;
            case 'books':
                return self::$next_book_id++;
            case 'members':
                return self::$next_member_id++;
            case 'loans':
                return self::$next_loan_id++;
            default:
                return 1;
        }
    }
}
?>