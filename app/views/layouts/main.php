<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Digital</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        .header { background: #007bff; color: white; padding: 15px; margin: -20px -20px 20px -20px; border-radius: 5px 5px 0 0; }
        .nav a { color: white; text-decoration: none; margin-right: 15px; }
        .nav a:hover { text-decoration: underline; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn { padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th, table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        table th { background: #f8f9fa; }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Biblioteca Digital</h1>
            <?php if (isset($_SESSION['user'])): ?>
                <div class="nav">
                    <a href="/projetoweb-servidor/dashboard">Dashboard</a>
                    <a href="/projetoweb-servidor/books">Livros</a>
                    <a href="/projetoweb-servidor/authors">Autores</a>
                    <a href="/projetoweb-servidor/users">Usuários</a>
                    <a href="/projetoweb-servidor/loans">Empréstimos</a>
                    <form method="POST" action="/projetoweb-servidor/logout" style="display: inline;">
                        <button type="submit" class="btn btn-danger">Sair</button>
                    </form>
                    <span style="float: right;">Logado como: <?= $_SESSION['user']['name'] ?></span>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php include "app/views/{$view}.php"; ?>
    </div>
</body>
</html>