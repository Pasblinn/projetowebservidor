<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?><?php echo env('APP_NAME', 'Sistema de Biblioteca'); ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url('css/style.css'); ?>" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Logo/Brand -->
            <a class="navbar-brand" href="<?php echo base_url('dashboard'); ?>">
                📚 <?php echo env('APP_NAME', 'Sistema de Biblioteca'); ?>
            </a>
            
            <!-- Toggle button para mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Menu de navegação -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']): ?>
                    <!-- Menu para usuários logados -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url('dashboard'); ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url('books'); ?>">Livros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url('members'); ?>">Membros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url('loans'); ?>">Empréstimos</a>
                        </li>
                    </ul>
                    
                    <!-- Informações do usuário -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                👤 <?php echo htmlspecialchars($_SESSION['nome']); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><span class="dropdown-header">Tipo: <?php echo ucfirst($_SESSION['tipo']); ?></span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('auth/logout'); ?>">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Container principal -->
    <div class="container my-4">
        
        <?php
        /**
         * Exibe mensagens de erro armazenadas na sessão
         * Remove as mensagens após exibir para evitar repetição
         */
        if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro!</strong>
                <ul class="mb-0">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        
        <?php
        /**
         * Exibe mensagens de sucesso armazenadas na sessão
         * Remove as mensagens após exibir para evitar repetição
         */
        if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sucesso!</strong> <?php echo htmlspecialchars($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php
        /**
         * Exibe mensagens informativas armazenadas na sessão
         * Remove as mensagens após exibir para evitar repetição
         */
        if (isset($_SESSION['info'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Info!</strong> <?php echo htmlspecialchars($_SESSION['info']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>