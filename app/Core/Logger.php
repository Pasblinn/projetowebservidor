<?php

namespace App\Core;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

/**
 * Logger - Sistema de logs usando Monolog
 *
 * Registra todas as ações importantes do sistema:
 * - Login/Logout de usuários
 * - Operações CRUD (livros, membros, empréstimos)
 * - Erros e exceções
 */
class Logger
{
    private static ?MonologLogger $logger = null;

    /**
     * Obtém a instância do logger
     */
    public static function getInstance(): MonologLogger
    {
        if (self::$logger === null) {
            self::$logger = new MonologLogger('biblioteca');

            // Diretório de logs
            $logDir = ROOT_PATH . '/storage/logs';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            // Handler para arquivo rotativo (um arquivo por dia)
            $handler = new RotatingFileHandler(
                $logDir . '/app.log',
                30, // Mantém logs dos últimos 30 dias
                MonologLogger::DEBUG
            );

            // Formato personalizado do log
            $formatter = new LineFormatter(
                "[%datetime%] %channel%.%level_name%: %message% %context%\n",
                "Y-m-d H:i:s"
            );
            $handler->setFormatter($formatter);

            self::$logger->pushHandler($handler);
        }

        return self::$logger;
    }

    /**
     * Registra uma informação
     */
    public static function info(string $message, array $context = []): void
    {
        self::getInstance()->info($message, $context);
    }

    /**
     * Registra um aviso
     */
    public static function warning(string $message, array $context = []): void
    {
        self::getInstance()->warning($message, $context);
    }

    /**
     * Registra um erro
     */
    public static function error(string $message, array $context = []): void
    {
        self::getInstance()->error($message, $context);
    }

    /**
     * Registra informações de debug
     */
    public static function debug(string $message, array $context = []): void
    {
        self::getInstance()->debug($message, $context);
    }

    /**
     * Registra login de usuário
     */
    public static function logLogin(string $username): void
    {
        self::info("Usuário logado", [
            'username' => $username,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    }

    /**
     * Registra logout de usuário
     */
    public static function logLogout(string $username): void
    {
        self::info("Usuário deslogado", [
            'username' => $username
        ]);
    }

    /**
     * Registra criação de livro
     */
    public static function logBookCreated(int $bookId, string $title): void
    {
        self::info("Livro criado", [
            'book_id' => $bookId,
            'title' => $title,
            'user' => $_SESSION['user']['username'] ?? 'unknown'
        ]);
    }

    /**
     * Registra atualização de livro
     */
    public static function logBookUpdated(int $bookId, string $title): void
    {
        self::info("Livro atualizado", [
            'book_id' => $bookId,
            'title' => $title,
            'user' => $_SESSION['user']['username'] ?? 'unknown'
        ]);
    }

    /**
     * Registra exclusão de livro
     */
    public static function logBookDeleted(int $bookId): void
    {
        self::warning("Livro excluído", [
            'book_id' => $bookId,
            'user' => $_SESSION['user']['username'] ?? 'unknown'
        ]);
    }

    /**
     * Registra criação de empréstimo
     */
    public static function logLoanCreated(int $loanId, int $bookId, int $memberId): void
    {
        self::info("Empréstimo criado", [
            'loan_id' => $loanId,
            'book_id' => $bookId,
            'member_id' => $memberId,
            'user' => $_SESSION['user']['username'] ?? 'unknown'
        ]);
    }

    /**
     * Registra devolução de empréstimo
     */
    public static function logLoanReturned(int $loanId): void
    {
        self::info("Livro devolvido", [
            'loan_id' => $loanId,
            'user' => $_SESSION['user']['username'] ?? 'unknown'
        ]);
    }
}
