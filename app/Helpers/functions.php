<?php

/**
 * Funções auxiliares globais
 */

if (!function_exists('env')) {
    /**
     * Obtém uma variável de ambiente
     */
    function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? $default;

        // Converte strings booleanas
        if (is_string($value)) {
            $lower = strtolower($value);
            if ($lower === 'true') return true;
            if ($lower === 'false') return false;
            if ($lower === 'null') return null;
        }

        return $value;
    }
}

if (!function_exists('base_url')) {
    /**
     * Retorna a URL base da aplicação
     */
    function base_url(string $path = ''): string
    {
        $baseUrl = rtrim(env('APP_URL', 'http://localhost'), '/');
        $basePath = rtrim(env('BASE_PATH', ''), '/');

        return $baseUrl . $basePath . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redireciona para uma URL
     */
    function redirect(string $path): void
    {
        header('Location: ' . base_url($path));
        exit;
    }
}

if (!function_exists('old')) {
    /**
     * Obtém valor antigo de formulário
     */
    function old(string $key, $default = '')
    {
        return $_SESSION['old_input'][$key] ?? $default;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Gera um token CSRF
     */
    function csrf_token(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Retorna um campo hidden com o token CSRF
     */
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('verify_csrf')) {
    /**
     * Verifica o token CSRF
     */
    function verify_csrf(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('escape')) {
    /**
     * Escapa HTML para prevenir XSS
     */
    function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die (para debug)
     */
    function dd(...$vars): void
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        die();
    }
}

if (!function_exists('session_flash')) {
    /**
     * Adiciona uma mensagem flash na sessão
     */
    function session_flash(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }
}

if (!function_exists('get_flash')) {
    /**
     * Obtém e remove uma mensagem flash da sessão
     */
    function get_flash(string $key, $default = null)
    {
        $value = $_SESSION[$key] ?? $default;
        unset($_SESSION[$key]);
        return $value;
    }
}
