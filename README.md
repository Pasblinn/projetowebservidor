# Sistema de Gerenciamento de Biblioteca

**UTFPR - Campus Ponta Grossa**
**Disciplina:** Web Servidor
**Ano:** 2025

**Desenvolvido por:**
- Pablo Juan Tadini Soto
- Vinícius Istchuk Volpato

---

## Sobre o Projeto

Este é um sistema completo para gerenciar bibliotecas, desenvolvido em **duas versões**:

1. **Sistema Web (PHP Puro + MVC)** - Interface web tradicional com autenticação e dashboard
2. **API REST (Laravel 9)** - API moderna para integração com aplicativos mobile, SPAs, etc.

O projeto permite cadastrar livros, membros e controlar empréstimos de forma completa, seguindo boas práticas de desenvolvimento e arquitetura de software.

### Estrutura do Repositório

```
projetowebservidor-main/
├── app/                    # Sistema Web (PHP Puro + MVC)
├── database/              # Scripts SQL compartilhados
├── storage/               # Logs do sistema web
├── biblioteca-api/        # API REST Laravel 9
├── test_api_simple.php    # Script de testes simples da API
├── test_api_complete.php  # Script com 31 testes completos
└── README.md              # Este arquivo
```

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
- **monolog/monolog** (^2.0) - Sistema profissional de logs
- **respect/validation** (^1.1) - Biblioteca robusta de validação de dados

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
  - `respect/validation` - Validação de dados
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

---

## 🚀 API REST (Laravel 9)

### Sobre a API

A API REST foi desenvolvida com **Laravel 9** e **Laravel Sanctum** para autenticação via token bearer. Ela oferece os mesmos recursos do sistema web, mas através de endpoints JSON para integração com aplicações mobile, SPAs (Single Page Applications) ou qualquer cliente HTTP.

### Recursos da API

- ✅ **Autenticação via Token Bearer** (Laravel Sanctum)
- ✅ **CRUD Completo** para Books, Members e Loans
- ✅ **Relacionamentos Eloquent** com eager loading
- ✅ **Validações Robustas** (Request Validation do Laravel)
- ✅ **Respostas JSON Padronizadas** (Trait ApiResponse)
- ✅ **Lógica de Negócio** (gerenciamento automático de estoque de livros)
- ✅ **Códigos HTTP Corretos** (200, 201, 400, 401, 404, 422)
- ✅ **Documentação Completa** + Coleções Postman/Insomnia

### Instalação da API

#### 1. Navegar para a pasta da API

```bash
cd biblioteca-api
```

#### 2. Instalar Dependências do Laravel

```bash
composer install
```

#### 3. Configurar .env da API

Copie o arquivo de exemplo:
```bash
cp .env.example .env
```

Edite `biblioteca-api/.env` com as configurações do banco:
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=biblioteca     # Mesmo banco do sistema web
DB_USERNAME=root
DB_PASSWORD=
```

#### 4. Criar Tabela do Sanctum

A API precisa da tabela `personal_access_tokens`:

```bash
php artisan migrate
```

**Se der erro**, crie manualmente via phpMyAdmin ou MySQL:
```sql
CREATE TABLE personal_access_tokens (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tokenable_type VARCHAR(255) NOT NULL,
  tokenable_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  token VARCHAR(64) NOT NULL UNIQUE,
  abilities TEXT NULL,
  last_used_at TIMESTAMP NULL,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX personal_access_tokens_tokenable_type_tokenable_id_index (tokenable_type, tokenable_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 5. Acessar a API

A API roda através do **Apache do XAMPP** (mesma porta do sistema web).

**URL Base da API:**
```
http://localhost/projetowebservidor-main/biblioteca-api/public/api
```

**Certifique-se que:**
- Apache está rodando no XAMPP Control Panel
- A pasta do projeto está em `C:\xampp\htdocs\projetowebservidor-main`

### Endpoints da API

| Método | Endpoint | Descrição | Autenticação |
|--------|----------|-----------|--------------|
| POST | `/api/login` | Login (retorna token) | ❌ Pública |
| POST | `/api/logout` | Logout (revoga token) | ✅ Token |
| GET | `/api/me` | Info do usuário logado | ✅ Token |
| GET | `/api/books` | Listar todos os livros | ✅ Token |
| POST | `/api/books` | Criar livro | ✅ Token |
| GET | `/api/books/{id}` | Buscar livro por ID | ✅ Token |
| PUT | `/api/books/{id}` | Atualizar livro | ✅ Token |
| DELETE | `/api/books/{id}` | Deletar livro | ✅ Token |
| GET | `/api/members` | Listar todos os membros | ✅ Token |
| POST | `/api/members` | Criar membro | ✅ Token |
| GET | `/api/members/{id}` | Buscar membro por ID | ✅ Token |
| PUT | `/api/members/{id}` | Atualizar membro | ✅ Token |
| DELETE | `/api/members/{id}` | Deletar membro | ✅ Token |
| GET | `/api/loans` | Listar empréstimos | ✅ Token |
| POST | `/api/loans` | Criar empréstimo | ✅ Token |
| GET | `/api/loans/{id}` | Buscar empréstimo por ID | ✅ Token |
| PUT | `/api/loans/{id}` | Registrar devolução | ✅ Token |
| DELETE | `/api/loans/{id}` | Deletar empréstimo | ✅ Token |

### Credenciais da API

- **Usuário:** `admin`
- **Senha:** `password`

### Testando a API

#### Opção 1: Postman ou Insomnia

**Importar coleção:**
- Postman: `biblioteca-api/postman_collection.json`
- Insomnia: `biblioteca-api/insomnia_collection.json`

**Configurar:**
1. Importe a coleção
2. Configure `base_url`: `http://localhost/projetowebservidor-main/biblioteca-api/public`
3. Execute o request "Login"
4. O token será salvo automaticamente
5. Teste os outros endpoints

#### Opção 2: Scripts PHP (Testes Automatizados)

**Testes Simples (5 testes básicos):**
```bash
php test_api_simple.php
```

**Testes Completos (31 testes):**
```bash
php test_api_complete.php
```

Resultado esperado: `✅ TODOS OS 31 TESTES PASSARAM!`

#### Opção 3: cURL (Linha de Comando)

**Login:**
```bash
curl -X POST http://localhost/projetowebservidor-main/biblioteca-api/public/api/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

**Listar livros (substitua {TOKEN}):**
```bash
curl -X GET http://localhost/projetowebservidor-main/biblioteca-api/public/api/books \
  -H "Authorization: Bearer {TOKEN}"
```

### Exemplo Completo de Uso

**1. Fazer Login:**
```http
POST /api/login
Content-Type: application/json

{
  "username": "admin",
  "password": "password"
}
```

**Resposta:**
```json
{
  "success": true,
  "message": "Login realizado com sucesso",
  "data": {
    "user": {
      "id": 1,
      "username": "admin",
      "nome": "Administrador",
      "tipo": "admin"
    },
    "token": "1|abc123xyz..."
  }
}
```

**2. Criar um Livro:**
```http
POST /api/books
Authorization: Bearer {token_do_login}
Content-Type: application/json

{
  "titulo": "Clean Code",
  "autor": "Robert C. Martin",
  "isbn": "9780132350884",
  "editora": "Prentice Hall",
  "ano_publicacao": 2008,
  "categoria": "Tecnologia",
  "quantidade_total": 3,
  "quantidade_disponivel": 3,
  "localizacao": "Estante A"
}
```

**Resposta:**
```json
{
  "success": true,
  "message": "Livro criado com sucesso",
  "data": {
    "id": 6,
    "titulo": "Clean Code",
    "autor": "Robert C. Martin",
    "isbn": "9780132350884",
    ...
  }
}
```

**3. Criar Empréstimo (Decrementa Estoque Automaticamente):**
```http
POST /api/loans
Authorization: Bearer {token}
Content-Type: application/json

{
  "member_id": 1,
  "book_id": 6,
  "data_emprestimo": "2025-01-15",
  "data_prevista_devolucao": "2025-01-29",
  "usuario_responsavel": "admin",
  "observacoes": "Primeiro empréstimo"
}
```

A API automaticamente:
- ✅ Verifica se o livro está disponível
- ✅ Cria o empréstimo
- ✅ **Decrementa** `quantidade_disponivel` do livro

**4. Registrar Devolução (Incrementa Estoque Automaticamente):**
```http
PUT /api/loans/1
Authorization: Bearer {token}
Content-Type: application/json

{
  "data_devolucao": "2025-01-25",
  "status": "devolvido",
  "observacoes": "Devolvido em perfeito estado"
}
```

A API automaticamente:
- ✅ Atualiza o empréstimo
- ✅ **Incrementa** `quantidade_disponivel` do livro
- ✅ Impede devoluções duplicadas

### Documentação Completa da API

Para mais detalhes, consulte:
```
biblioteca-api/README.md
```

### Problemas Comuns da API

**Erro "Table personal_access_tokens doesn't exist":**
```bash
cd biblioteca-api
php artisan migrate
```

Ou crie manualmente (SQL acima).

**Erro "Unauthenticated":**
- Certifique-se de enviar o header: `Authorization: Bearer {token}`
- Verifique se fez login e obteve o token

**Servidor não inicia:**
```bash
cd biblioteca-api  # Certifique-se de estar na pasta correta
php artisan serve
```

---

## Funcionalidades Principais

### Sistema Web

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

**Sistema Web (PHP Puro):**
- Criei toda a estrutura de classes com namespaces e orientação a objetos
- Implementei o sistema de rotas do zero (Router.php)
- Fiz a integração com o banco usando PDO (Database.php com Singleton)
- Desenvolvi as Entities (Book, Member, Loan, User) com getters e setters
- Criei os Repositories para acesso ao banco
- Implementei a lógica dos Controllers
- Configurei o Composer com autoload PSR-4
- Integrei o sistema de .env para configurações

**API REST (Laravel 9):**
- Estruturei toda a API REST com Laravel 9
- Implementei autenticação via Laravel Sanctum (bearer tokens)
- Criei Controllers da API (AuthController, BookController, MemberController, LoanController)
- Desenvolvi Models Eloquent com relacionamentos
- Implementei lógica de negócio (gerenciamento automático de estoque)
- Criei validações de Request do Laravel
- Desenvolvi Trait ApiResponse para padronização de respostas JSON
- Configurei rotas da API e middleware de autenticação
- Criei coleções de teste (Postman/Insomnia)
- Desenvolvi scripts automatizados de testes (31 testes completos)

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
- ✅ **Composer** com autoload PSR-4 e uso de 3 packages externos
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
