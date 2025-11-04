<?php

/**
 * Ponto de entrada principal da aplicação
 * Sistema de Gerenciamento de Biblioteca
 *
 * Este arquivo inicializa a aplicação, configura o ambiente
 * e direciona as requisições através do sistema de rotas
 */

// Define o diretório raiz da aplicação
define('ROOT_PATH', __DIR__);

// Carrega o autoloader do Composer
require_once ROOT_PATH . '/vendor/autoload.php';

// Carrega variáveis de ambiente do arquivo .env
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

// Carrega funções auxiliares
require_once ROOT_PATH . '/app/Helpers/functions.php';

// Inicia a sessão para controle de autenticação
session_start();

// Define timezone conforme configuração
date_default_timezone_set(env('TIMEZONE', 'America/Sao_Paulo'));

// Configurações de erro (apenas em desenvolvimento)
if (env('APP_ENV') === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Carrega e executa as rotas
$router = require_once ROOT_PATH . '/app/Config/routes.php';
$router->dispatch();
