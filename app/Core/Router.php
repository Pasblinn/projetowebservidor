<?php

namespace App\Core;

/**
 * Classe Router - Sistema de rotas para URLs transparentes
 */
class Router
{
    private array $routes = [];
    private string $basePath;

    public function __construct(string $basePath = '')
    {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * Adiciona uma rota GET
     */
    public function get(string $path, string $controller, string $method): void
    {
        $this->addRoute('GET', $path, $controller, $method);
    }

    /**
     * Adiciona uma rota POST
     */
    public function post(string $path, string $controller, string $method): void
    {
        $this->addRoute('POST', $path, $controller, $method);
    }

    /**
     * Adiciona uma rota (qualquer método HTTP)
     */
    private function addRoute(string $httpMethod, string $path, string $controller, string $method): void
    {
        $this->routes[] = [
            'http_method' => $httpMethod,
            'path' => $path,
            'controller' => $controller,
            'method' => $method
        ];
    }

    /**
     * Despacha a requisição para o controlador apropriado
     */
    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];

        // Remove query string
        if (($pos = strpos($requestUri, '?')) !== false) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        // Remove base path
        if (!empty($this->basePath) && strpos($requestUri, $this->basePath) === 0) {
            $requestUri = substr($requestUri, strlen($this->basePath));
        }

        $requestUri = '/' . trim($requestUri, '/');

        // Procura por uma rota correspondente
        foreach ($this->routes as $route) {
            if ($route['http_method'] !== $requestMethod) {
                continue;
            }

            $pattern = $this->convertPathToRegex($route['path']);

            if (preg_match($pattern, $requestUri, $matches)) {
                $this->callController($route['controller'], $route['method'], $matches);
                return;
            }
        }

        // Rota não encontrada
        $this->handleNotFound();
    }

    /**
     * Converte o path da rota em expressão regular
     */
    private function convertPathToRegex(string $path): string
    {
        // Converte :param em regex para capturar parâmetros
        $pattern = preg_replace('/\/:([a-zA-Z0-9_]+)/', '/(?P<$1>[^/]+)', $path);

        // Permite paths opcionais
        $pattern = str_replace('?', '?', $pattern);

        return '#^' . $pattern . '$#';
    }

    /**
     * Chama o controlador e método apropriados
     */
    private function callController(string $controllerClass, string $method, array $params = []): void
    {
        // Remove índices numéricos dos parâmetros
        $namedParams = array_filter($params, function($key) {
            return !is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);

        if (!class_exists($controllerClass)) {
            $this->handleNotFound();
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            $this->handleNotFound();
            return;
        }

        // Chama o método do controlador passando os parâmetros
        call_user_func_array([$controller, $method], $namedParams);
    }

    /**
     * Trata rotas não encontradas (404)
     */
    private function handleNotFound(): void
    {
        http_response_code(404);
        echo "<h1>404 - Página não encontrada</h1>";
        echo "<p>A página que você procura não existe.</p>";
        exit;
    }

    /**
     * Gera URL a partir de um path
     */
    public function url(string $path): string
    {
        return $this->basePath . '/' . ltrim($path, '/');
    }
}
