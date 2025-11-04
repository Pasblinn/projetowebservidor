<?php

namespace App\Controllers;

/**
 * Classe base para todos os controladores
 */
abstract class BaseController
{
    /**
     * Carrega uma view
     */
    protected function loadView(string $view, array $data = []): void
    {
        // Extrai variáveis para a view
        extract($data);

        // Define caminho da view
        $viewFile = dirname(__DIR__) . '/views/' . $view . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View não encontrada: $view");
        }
    }

    /**
     * Retorna JSON response
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Verifica se a requisição é POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Verifica se a requisição é GET
     */
    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Obtém dados do POST
     */
    protected function getPost(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Obtém dados do GET
     */
    protected function getQuery(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
}
