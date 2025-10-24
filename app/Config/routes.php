<?php

/**
 * Definição de rotas da aplicação
 * Mapeia URLs para Controllers e métodos
 */

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\BooksController;
use App\Controllers\MembersController;
use App\Controllers\LoansController;

// Cria instância do Router
$basePath = env('BASE_PATH', '');
$router = new Router($basePath);

// Rotas de autenticação
$router->get('/auth', AuthController::class, 'index');
$router->post('/auth/login', AuthController::class, 'login');
$router->get('/auth/logout', AuthController::class, 'logout');

// Dashboard (rota raiz)
$router->get('/', DashboardController::class, 'index');
$router->get('/dashboard', DashboardController::class, 'index');

// Rotas de livros
$router->get('/books', BooksController::class, 'index');
$router->get('/books/create', BooksController::class, 'create');
$router->post('/books/store', BooksController::class, 'store');
$router->get('/books/edit', BooksController::class, 'edit');
$router->post('/books/update', BooksController::class, 'update');
$router->get('/books/delete', BooksController::class, 'delete');

// Rotas de membros
$router->get('/members', MembersController::class, 'index');
$router->get('/members/create', MembersController::class, 'create');
$router->post('/members/store', MembersController::class, 'store');
$router->get('/members/edit', MembersController::class, 'edit');
$router->post('/members/update', MembersController::class, 'update');
$router->get('/members/delete', MembersController::class, 'delete');

// Rotas de empréstimos
$router->get('/loans', LoansController::class, 'index');
$router->get('/loans/create', LoansController::class, 'create');
$router->post('/loans/store', LoansController::class, 'store');
$router->get('/loans/devolver', LoansController::class, 'devolver');
$router->get('/loans/delete', LoansController::class, 'delete');

return $router;
