# Sistema de Gerenciamento de Biblioteca

**UTFPR - Campus Ponta Grossa**
**Disciplina:** Web Servidor
**Ano:** 2025

**Desenvolvido por:**
- Pablo Juan Tadini Soto
- Vinícius Istchuk Volpato

---

## Sobre o Projeto

Este é um sistema web para gerenciar bibliotecas, desenvolvido em PHP usando orientação a objetos e banco de dados MySQL. O projeto permite cadastrar livros, membros e controlar empréstimos de forma completa.

Começamos com uma versão mais simples e fomos melhorando ao longo do desenvolvimento, adicionando recursos mais avançados como rotas limpas, arquitetura MVC e integração com banco de dados real.

## O que o sistema faz

- **Gerenciar Livros:** cadastrar, editar, listar e remover livros do acervo
- **Gerenciar Membros:** cadastro completo de pessoas que podem pegar livros emprestados
- **Controlar Empréstimos:** registrar quando um livro é emprestado e quando é devolvido
- **Sistema de Login:** só pessoas autorizadas podem acessar o sistema
- **Dashboard:** painel com estatísticas e resumo do que está acontecendo na biblioteca

## Tecnologias Usadas

### Linguagens e Frameworks
- PHP 8.0
- MySQL (banco de dados)
- HTML/CSS
- JavaScript
- Bootstrap 5 (para deixar bonito e responsivo)

### Ferramentas e Packages
- **Composer** (gerenciador de dependências do PHP)
- **Apache** (servidor web)
- **PDO** (para conectar com o banco de forma segura)

### Packages Composer Utilizados
- **vlucas/phpdotenv** (^5.6) - Gerenciamento de variáveis de ambiente (.env)
- **monolog/monolog** (^3.9) - Sistema profissional de logs
- **ramsey/uuid** (^4.9) - Geração de identificadores únicos (UUIDs)
- **respect/validation** (^2.4) - Biblioteca robusta de validação de dados
- **symfony/var-dumper** (^6.4 - dev) - Ferramentas de debug para desenvolvimento

## Estrutura do Código

Organizamos o código seguindo o padrão MVC (Model-View-Controller):

```
app/
├── Controllers/     # Lógica de controle (o que acontece quando clica em algo)
├── Models/
│   ├── Entities/    # Representam objetos (Livro, Membro, etc)
│   └── Repositories/# Fazem as consultas no banco
├── Core/            # Classes principais (Router, Database, Logger, Validator)
├── views/           # Páginas HTML que o usuário vê
├── Config/          # Configurações e rotas
└── Helpers/         # Funções auxiliares

database/
├── schema.sql       # Script que cria as tabelas
└── seed.sql         # Dados de exemplo para testar

storage/
└── logs/            # Arquivos de log do sistema
```

## Banco de Dados

O sistema usa 4 tabelas principais:

- **users** - usuários que podem fazer login
- **books** - catálogo de livros
- **members** - pessoas cadastradas que podem pegar livros
- **loans** - registro de empréstimos (quem pegou qual livro e quando)

## Como Funciona

### Sistema de Rotas
Implementamos um sistema próprio de rotas para ter URLs limpas:
- `/books` - lista de livros
- `/books/create` - cadastrar novo livro
- `/members` - lista de membros
- `/loans` - empréstimos

Ao invés de URLs feias tipo `index.php?page=books&action=create`

### Orientação a Objetos
Todo o código usa classes e objetos. Por exemplo, quando buscamos um livro no banco, ele vira um objeto `Book` com métodos tipo `getTitulo()`, `getAutor()`, etc.

### PDO e Segurança
Usamos PDO com prepared statements em todas as consultas ao banco para prevenir SQL injection. As senhas são criptografadas com `password_hash()`.

### Sistema de Logs (Monolog)
Implementamos um sistema profissional de logs que registra:
- Todas as operações de login/logout
- Criação, edição e exclusão de livros
- Registro e devolução de empréstimos
- Erros e exceções do sistema

Os logs ficam armazenados em `storage/logs/app.log` com rotação diária (mantém 30 dias).

### Validação de Dados (Respect\Validation)
Utilizamos a biblioteca Respect\Validation para validações robustas:
- Validação de CPF brasileiro
- Validação de email
- Validação de ISBN (10 ou 13 dígitos)
- Validação de telefone
- Validações personalizadas de livros e membros

Isso garante dados consistentes e seguros no sistema.

## Instalação

### Requisitos do Sistema

Antes de começar, certifique-se de ter instalado:
- **PHP 8.0 ou superior** (testado com PHP 8.0.30)
- **MySQL 5.7+** ou **MariaDB 10.3+**
- **Apache 2.4+** com mod_rewrite habilitado
- **Composer 2.0+** (gerenciador de dependências)

### Passo a Passo

#### 1. Baixar o Projeto

Clone o repositório:
```bash
git clone https://github.com/Pasblinn/projetowebservidor.git
```

Ou baixe o ZIP e extraia para a pasta do seu servidor web:
- **XAMPP Windows:** `C:\xampp\htdocs\projetowebservidor`
- **Linux:** `/var/www/html/projetowebservidor`

#### 2. Instalar Dependências com Composer

Entre na pasta do projeto e execute:
```bash
cd projetowebservidor
composer install
```

Isso vai:
- Baixar todos os packages necessários:
  - `vlucas/phpdotenv` - Gerencia variáveis de ambiente
  - `monolog/monolog` - Sistema de logs
  - `ramsey/uuid` - Geração de UUIDs
  - `respect/validation` - Validação de dados
  - `symfony/var-dumper` - Debug (apenas dev)
- Criar a pasta `vendor/` com todas as dependências
- Configurar o autoload PSR-4

#### 3. Configurar o Banco de Dados

**3.1. Criar o banco:**

Via phpMyAdmin (http://localhost/phpmyadmin):
1. Clique em "Novo"
2. Nome: `biblioteca`
3. Collation: `utf8mb4_unicode_ci`
4. Clique em "Criar"

Ou via linha de comando:
```bash
mysql -u root -p
CREATE DATABASE biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**3.2. Criar as tabelas:**

No phpMyAdmin:
1. Selecione o banco `biblioteca`
2. Vá na aba "SQL"
3. Abra o arquivo `database/schema.sql` e copie todo o conteúdo
4. Cole e clique em "Executar"

Ou via linha de comando:
```bash
mysql -u root -p biblioteca < database/schema.sql
```

Isso cria as 4 tabelas: `users`, `books`, `members` e `loans`

**3.3. Inserir dados de exemplo:**

Ainda no phpMyAdmin, aba "SQL":
1. Copie o conteúdo de `database/seed.sql`
2. Cole e execute

Ou via linha de comando:
```bash
mysql -u root -p biblioteca < database/seed.sql
```

Isso adiciona 2 usuários, 5 livros, 3 membros e alguns empréstimos de exemplo.

#### 4. Configurar o Arquivo .env

**4.1. Copiar o modelo:**
```bash
cp .env.example .env
```

No Windows, copie manualmente `.env.example` e renomeie para `.env`

**4.2. Editar as configurações:**

Abra o arquivo `.env` e ajuste:

```env
# Configurações do Banco de Dados
DB_HOST=localhost        # Endereço do MySQL (geralmente localhost)
DB_PORT=3306            # Porta do MySQL (padrão: 3306)
DB_DATABASE=biblioteca  # Nome do banco que você criou
DB_USERNAME=root        # Seu usuário do MySQL
DB_PASSWORD=            # Senha do MySQL (vazio no XAMPP padrão)

# Configurações da Aplicação
BASE_PATH=/projetowebservidor  # IMPORTANTE: ajuste conforme o nome da sua pasta
```

**Atenção ao BASE_PATH:**
- Se sua pasta é `C:\xampp\htdocs\projetowebservidor` → use `BASE_PATH=/projetowebservidor`
- Se sua pasta é `C:\xampp\htdocs\biblioteca` → use `BASE_PATH=/biblioteca`
- Se está na raiz do htdocs → deixe vazio: `BASE_PATH=`

#### 5. Configurar o Apache (mod_rewrite)

O sistema usa URLs limpas, então precisa do mod_rewrite habilitado.

**No XAMPP Windows:**
1. Abra `C:\xampp\apache\conf\httpd.conf`
2. Procure por `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Remova o `#` do início da linha
4. Salve e reinicie o Apache no XAMPP Control Panel

**No Linux:**
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### 6. Verificar o arquivo .htaccess

Na raiz do projeto, abra o arquivo `.htaccess` e confirme que a linha 4 está assim:
```apache
RewriteBase /projetowebservidor/
```

Ajuste para corresponder ao seu `BASE_PATH` (se sua pasta tem outro nome).

#### 7. Acessar o Sistema

Abra o navegador e vá para:
```
http://localhost/projetowebservidor
```

Você deve ver a tela de login!

### Credenciais de Acesso

Use para fazer login:
- **Usuário:** `admin`
- **Senha:** `admin123`

Ou:
- **Usuário:** `bibliotecario`
- **Senha:** `admin123`

### Problemas Comuns

**Erro "vendor/autoload.php not found":**
- Solução: Execute `composer install` na pasta do projeto

**Erro "Access denied for user":**
- Solução: Verifique as credenciais do MySQL no arquivo `.env`

**Erro 404 nas páginas:**
- Solução: Habilite o mod_rewrite do Apache (veja passo 5)

**Páginas sem estilo (CSS):**
- Solução: Ajuste o `BASE_PATH` no arquivo `.env`

## Funcionalidades Principais

### Livros
- Listagem com todos os livros cadastrados
- Formulário para adicionar novos livros
- Edição de livros existentes
- Exclusão de livros
- Validação de ISBN único
- Controle de quantidade disponível

### Membros
- Cadastro completo (nome, CPF, email, telefone, endereço)
- Validação de CPF e email únicos
- Categorização (estudante, professor, comunidade)
- Listagem de todos os membros

### Empréstimos
- Registro de quando um livro é emprestado
- Atualização automática da disponibilidade do livro
- Registro de devolução
- Listagem de empréstimos ativos
- Identificação de empréstimos atrasados

### Autenticação
- Login seguro
- Controle de sessão
- Logout
- Proteção de páginas (precisa estar logado para acessar)

## Distribuição de Tarefas

### Pablo Juan Tadini Soto
Fiquei responsável pela parte mais técnica do backend:

- Criei toda a estrutura de classes com namespaces e orientação a objetos
- Implementei o sistema de rotas do zero (Router.php)
- Fiz a integração com o banco usando PDO (Database.php com Singleton)
- Desenvolvi as Entities (Book, Member, Loan, User) com getters e setters
- Criei os Repositories para acesso ao banco
- Implementei a lógica dos Controllers
- Configurei o Composer com autoload PSR-4
- Integrei o sistema de .env para configurações

### Vinícius Istchuk Volpato
Trabalhei mais na parte visual e banco de dados:

- Desenvolvi todas as telas (views) em HTML
- Integrei o Bootstrap para deixar responsivo
- Criei os scripts SQL (schema.sql e seed.sql)
- Fiz a parte de formulários e validação visual
- Implementei o sistema de mensagens de sucesso e erro
- Testei todas as funcionalidades
- Ajudei na documentação

## Observações Técnicas

O projeto atende todos os requisitos da disciplina:
- ✅ **PHP 8+** com orientação a objetos completa (classes, interfaces, namespaces, herança)
- ✅ **Composer** com autoload PSR-4 e uso de 5 packages externos
- ✅ **Banco de dados MySQL** via PDO com prepared statements
- ✅ **Sistema de rotas** próprio para URLs transparentes
- ✅ **Padrão MVC** completo (Models, Views, Controllers + Repository pattern)
- ✅ **Validações robustas** no servidor usando Respect\Validation
- ✅ **Sistema de logs** profissional com Monolog
- ✅ **Interface adequada** com Bootstrap 5 e feedback visual
- ✅ **Documentação completa** de instalação e configuração

### Uso dos Packages Composer

O projeto demonstra uso real e prático de packages Composer:

1. **vlucas/phpdotenv**: Usado em `index.php` para carregar configurações do `.env`
2. **monolog/monolog**: Sistema de logs em `app/Core/Logger.php`, usado em todos os controllers
3. **respect/validation**: Validações em `app/Core/Validator.php`, usado para validar CPF, email, ISBN, etc.
4. **ramsey/uuid**: Disponível para geração de identificadores únicos
5. **symfony/var-dumper**: Ferramenta de debug para desenvolvimento

Tentamos seguir boas práticas de desenvolvimento, separando bem as responsabilidades de cada parte do código e pensando em segurança (principalmente contra SQL injection e XSS).

## Melhorias ao Longo do Desenvolvimento

Durante o desenvolvimento, fomos evoluindo o projeto:

- Começamos guardando dados em arrays de sessão, depois migramos para banco de dados real
- Melhoramos a organização do código, separando em camadas (MVC + Repository)
- Adicionamos o Composer para gerenciar dependências
- Implementamos packages Composer úteis (Monolog, Respect\Validation, etc.)
- Criamos um sistema de rotas próprio para URLs mais limpas
- Aplicamos tipagem forte nas classes para aproveitar recursos do PHP 8
- Adicionamos sistema de logs profissional
- Implementamos validações robustas com biblioteca externa
- Melhoramos a interface usando Bootstrap

## Repositório

https://github.com/Pasblinn/projetowebservidor

---

**Sistema de Gerenciamento de Biblioteca - 2025**
