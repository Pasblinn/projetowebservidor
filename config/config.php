<?php
/**
 * Arquivo de configuração principal da aplicação
 * Define constantes e configurações globais do sistema
 */

// Define a URL base da aplicação
define('BASE_URL', 'http://localhost/projetoweb-servidor/');

// Define o título da aplicação
define('APP_NAME', 'Sistema de Gerenciamento de Biblioteca');

// Configurações de sessão
define('SESSION_TIMEOUT', 3600); // 1 hora em segundos

// Configurações de validação
define('MIN_PASSWORD_LENGTH', 6);
define('MAX_USERNAME_LENGTH', 50);

// Configurações de paginação
define('ITEMS_PER_PAGE', 10);

// Define o timezone da aplicação
date_default_timezone_set('America/Sao_Paulo');

// Configurações de erro para desenvolvimento
// Em produção, estas configurações devem ser alteradas
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>