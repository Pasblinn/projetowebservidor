<?php
/**
 * Ponto de entrada principal da aplicação
 * Sistema de Gerenciamento de Biblioteca
 * 
 * Este arquivo inicializa a aplicação, configura o roteamento
 * e direciona as requisições para os controladores adequados
 */

// Inicia a sessão para controle de autenticação
session_start();

// Define o diretório raiz da aplicação
define('ROOT_PATH', __DIR__);

// Inclui os arquivos de configuração e classes necessárias
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/includes/autoload.php';

// Obtém a URL requisitada, removendo parâmetros GET se existirem
$request = isset($_GET['url']) ? trim($_GET['url'], '/') : '';

// Define o controlador padrão se nenhuma URL for especificada
if (empty($request)) {
    $request = 'home';
}

// Divide a URL em partes para identificar controlador e ação
$url_parts = explode('/', $request);
$controller_name = ucfirst($url_parts[0]) . 'Controller';
$action = isset($url_parts[1]) ? $url_parts[1] : 'index';

// Caminho completo para o arquivo do controlador
$controller_file = ROOT_PATH . '/app/controllers/' . $controller_name . '.php';

// Verifica se o arquivo do controlador existe
if (file_exists($controller_file)) {
    // Inclui o controlador solicitado
    require_once $controller_file;
    
    // Instancia o controlador se a classe existir
    if (class_exists($controller_name)) {
        $controller = new $controller_name();
        
        // Executa a ação solicitada se o método existir
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            // Ação não encontrada, executa index como fallback
            $controller->index();
        }
    } else {
        // Classe do controlador não encontrada
        http_response_code(404);
        echo "Controlador não encontrado";
    }
} else {
    // Arquivo do controlador não existe
    http_response_code(404);
    echo "Página não encontrada";
}
?>