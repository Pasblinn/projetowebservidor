<?php
/**
 * Sistema de autoload para carregar automaticamente as classes
 * Este arquivo é responsável por incluir automaticamente
 * os arquivos de modelo quando uma classe é instanciada
 */

spl_autoload_register(function ($class_name) {
    // Define os diretórios onde as classes podem estar localizadas
    $directories = [
        ROOT_PATH . '/app/models/',
        ROOT_PATH . '/app/controllers/',
        ROOT_PATH . '/includes/'
    ];
    
    // Procura pela classe em cada diretório
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        
        // Se o arquivo existe, inclui e para a busca
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
?>