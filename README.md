# Sistema de Gerenciamento de Biblioteca

Sistema web desenvolvido em PHP para gerenciar livros, membros e empréstimos de uma biblioteca.

## Integrantes do Projeto

- **Pablo Juan Tadini Soto**
- **Vinícius Istchuk Volpato**

## Descrição do Projeto

Sistema completo de gerenciamento de biblioteca desenvolvido com arquitetura MVC (Model-View-Controller), permitindo o controle de livros, membros e empréstimos. O sistema utiliza PHP 8+ e não requer banco de dados, simulando o armazenamento através de arrays em sessão.

## Funcionalidades Implementadas

### Autenticação
- Sistema de login com validação
- Controle de sessão com timeout
- Logout seguro
- Senhas criptografadas

### Gerenciamento de Livros
- Listagem completa de livros
- Cadastro de novos livros
- Edição de livros existentes
- Exclusão de livros
- Validações completas no servidor

### Gerenciamento de Membros
- Listagem completa de membros
- Cadastro de novos membros
- Edição de membros existentes
- Exclusão de membros
- Validações completas no servidor

### Gerenciamento de Empréstimos
- Sistema de controle de empréstimos
- Registro de datas de empréstimo e devolução
- Controle de status dos empréstimos

### Dashboard
- Página inicial com estatísticas
- Resumo do sistema
- Navegação intuitiva

### Interface
- Design responsivo com Bootstrap 5
- Mensagens de feedback para usuário
- Tratamento de erros
- Formulários com validação

## Distribuição de Atividades

### Pablo Juan Tadini Soto
- Desenvolvimento da arquitetura MVC
- Implementação do sistema de autenticação
- Desenvolvimento dos controllers (AuthController, BooksController, MembersController, LoansController)
- Implementação das validações PHP
- Sistema de roteamento e autoload
- Configuração do ambiente e arquivos de configuração

### Vinícius Istchuk Volpato
- Desenvolvimento das views e interface do usuário
- Implementação do layout responsivo
- Criação dos modelos de dados (Database, User)
- Desenvolvimento das páginas de CRUD (Books, Members, Loans)
- Estilização e integração do Bootstrap
- Testes e validação do sistema

## Tecnologias Utilizadas

- **PHP 8.0+**: Linguagem principal
- **Apache**: Servidor web com mod_rewrite
- **Bootstrap 5**: Framework CSS
- **HTML5/CSS3**: Interface
- **JavaScript**: Interatividade

## Estrutura do Projeto

```
projetoweb-servidor/
├── app/
│   ├── controllers/          # Controladores (lógica de negócio)
│   │   ├── AuthController.php
│   │   ├── BooksController.php
│   │   ├── DashboardController.php
│   │   ├── HomeController.php
│   │   ├── LoansController.php
│   │   └── MembersController.php
│   ├── models/              # Modelos (acesso aos dados)
│   │   ├── Database.php
│   │   └── User.php
│   └── views/               # Views (interface do usuário)
│       ├── auth/
│       ├── books/
│       ├── dashboard/
│       ├── home/
│       ├── layout/
│       ├── loans/
│       └── members/
├── config/                  # Arquivos de configuração
│   ├── config.php
│   └── database.php
├── css/                     # Estilos CSS
├── images/                  # Imagens do sistema
├── includes/               # Arquivos auxiliares
│   └── autoload.php
├── js/                     # JavaScript
├── .htaccess               # Configuração Apache
├── index.php               # Entrada principal
├── INSTALACAO.md           # Documentação de instalação
└── README.md               # Este arquivo
```

## Instalação e Configuração

Consulte o arquivo [INSTALACAO.md](INSTALACAO.md) para instruções detalhadas de instalação e configuração do sistema.

### Instalação Rápida

1. **Copie o projeto** para o diretório do servidor web:
   ```bash
   # XAMPP Windows
   C:\xampp\htdocs\projetoweb-servidor\

   # XAMPP Linux
   /opt/lampp/htdocs/projetoweb-servidor/
   ```

2. **Inicie o Apache** no XAMPP

3. **Acesse o sistema**:
   ```
   http://localhost/projetoweb-servidor/
   ```

4. **Usuários de teste**:
   - Admin: `admin` / `password`
   - Bibliotecário: `bibliotecario` / `password`

## Requisitos do Sistema

- PHP 8.0 ou superior
- Servidor Apache com mod_rewrite habilitado
- Navegador web moderno

## Particularidades e Observações

### Armazenamento de Dados
O sistema utiliza arrays PHP em sessão para simular um banco de dados, conforme especificação do projeto. Os dados persistem durante a sessão do navegador.

### Validações
Todas as validações são realizadas no servidor (PHP), garantindo segurança e integridade dos dados.

### Segurança
- Senhas criptografadas com `password_hash()`
- Escape de dados HTML com `htmlspecialchars()`
- Validação de entrada no servidor
- Controle de sessão com timeout
- Verificação de autenticação em todas as páginas protegidas

### Funcionalidades Faltantes
- Relatórios em PDF
- Sistema de multas por atraso
- Renovação automática de empréstimos
- Sistema de reservas de livros

### Bugs Conhecidos
- Nenhum bug crítico identificado
- Sistema totalmente funcional

## Estrutura de URLs

- `/` - Dashboard (página inicial)
- `/auth` - Login
- `/auth/logout` - Logout
- `/books` - Lista de livros
- `/books/create` - Novo livro
- `/books/edit?id=X` - Editar livro
- `/members` - Lista de membros
- `/members/create` - Novo membro
- `/members/edit?id=X` - Editar membro
- `/loans` - Lista de empréstimos
- `/loans/create` - Novo empréstimo

## Solução de Problemas

### Erro 404
- Verifique se o mod_rewrite está habilitado no Apache
- Confirme que o arquivo `.htaccess` existe no diretório raiz

### CSS/JavaScript não carrega
- Confirme que o BASE_URL está correto em `config/config.php`
- Verifique se os arquivos CSS e JS estão nas pastas corretas

### Erro de Sessão
- Verifique se a sessão pode ser iniciada no PHP
- Confirme as permissões do diretório de sessões

## Licença

Projeto acadêmico desenvolvido para a disciplina de Desenvolvimento Web.

---

**Versão**: 1.0
**Data**: Outubro de 2025
