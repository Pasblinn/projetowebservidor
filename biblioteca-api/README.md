# Biblioteca API - Sistema de Gerenciamento de Biblioteca

API REST desenvolvida em Laravel 9 para gerenciamento de biblioteca, incluindo controle de livros, membros e empréstimos.

## Requisitos

- PHP 8.0 ou superior
- MySQL 5.7 ou superior
- Composer
- XAMPP (recomendado para ambiente Windows)

## Instalação

1. Clone o repositório e navegue até a pasta do projeto:
```bash
cd biblioteca-api
```

2. Instale as dependências do Composer:
```bash
composer install
```

3. Configure o arquivo `.env` com as credenciais do banco de dados:
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=biblioteca
DB_USERNAME=root
DB_PASSWORD=
```

4. Execute as migrações do Sanctum (tabela de tokens):
```bash
php artisan migrate
```

5. Inicie o servidor de desenvolvimento:
```bash
php artisan serve
```

A API estará disponível em: `http://localhost:8000`

## Autenticação

A API utiliza Laravel Sanctum para autenticação via Bearer Token.

### Login
```http
POST /api/login
Content-Type: application/json

{
  "username": "admin",
  "password": "password"
}
```

**Resposta de sucesso:**
```json
{
  "success": true,
  "message": "Login realizado com sucesso",
  "data": {
    "user": {
      "id": 1,
      "username": "admin",
      "email": "admin@biblioteca.com",
      "nome": "Administrador",
      "tipo": "admin"
    },
    "token": "1|abc123..."
  }
}
```

### Usando o Token

Todas as rotas protegidas requerem o token no header:
```http
Authorization: Bearer {seu_token_aqui}
```

## Endpoints da API

### Autenticação

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| POST | `/api/login` | Fazer login e obter token |
| POST | `/api/logout` | Fazer logout (revoga token) |
| GET | `/api/me` | Obter informações do usuário autenticado |

### Livros (Books)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/api/books` | Listar todos os livros |
| GET | `/api/books/{id}` | Obter detalhes de um livro |
| POST | `/api/books` | Criar novo livro |
| PUT | `/api/books/{id}` | Atualizar livro |
| DELETE | `/api/books/{id}` | Deletar livro |

**Exemplo de criação de livro:**
```json
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

### Membros (Members)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/api/members` | Listar todos os membros |
| GET | `/api/members/{id}` | Obter detalhes de um membro |
| POST | `/api/members` | Criar novo membro |
| PUT | `/api/members/{id}` | Atualizar membro |
| DELETE | `/api/members/{id}` | Deletar membro |

**Exemplo de criação de membro:**
```json
{
  "nome": "João Silva",
  "email": "joao@email.com",
  "telefone": "(42) 99999-9999",
  "endereco": "Rua A, 123",
  "cpf": "123.456.789-00",
  "data_nascimento": "1990-01-15",
  "categoria": "estudante",
  "ativo": true
}
```

**Categorias válidas:** `estudante`, `professor`, `comunidade`

### Empréstimos (Loans)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/api/loans` | Listar todos os empréstimos |
| GET | `/api/loans/{id}` | Obter detalhes de um empréstimo |
| POST | `/api/loans` | Criar novo empréstimo |
| PUT | `/api/loans/{id}` | Registrar devolução |
| DELETE | `/api/loans/{id}` | Deletar empréstimo |

**Exemplo de criação de empréstimo:**
```json
{
  "member_id": 1,
  "book_id": 1,
  "data_emprestimo": "2025-01-15",
  "data_prevista_devolucao": "2025-01-29",
  "usuario_responsavel": "admin",
  "observacoes": "Primeira renovação"
}
```

**Exemplo de devolução:**
```json
{
  "data_devolucao": "2025-01-25",
  "status": "devolvido",
  "observacoes": "Devolvido em perfeito estado"
}
```

## Respostas da API

Todas as respostas seguem o formato JSON padronizado:

**Sucesso:**
```json
{
  "success": true,
  "message": "Operação realizada com sucesso",
  "data": { ... }
}
```

**Erro:**
```json
{
  "success": false,
  "message": "Descrição do erro",
  "errors": { ... }
}
```

## Códigos de Status HTTP

| Código | Descrição |
|--------|-----------|
| 200 | Sucesso |
| 201 | Criado com sucesso |
| 400 | Requisição inválida |
| 401 | Não autenticado |
| 403 | Não autorizado |
| 404 | Recurso não encontrado |
| 422 | Erro de validação |
| 500 | Erro interno do servidor |

## Testando a API

### Coleção Insomnia/Postman

Uma coleção completa de testes está disponível no arquivo `insomnia_collection.json`.

**Para importar no Insomnia:**
1. Abra o Insomnia
2. Clique em "Application" > "Preferences" > "Data" > "Import Data"
3. Selecione o arquivo `insomnia_collection.json`
4. Configure a variável `base_url` no ambiente (padrão: `http://localhost:8000`)
5. Faça login e copie o token retornado para a variável `token`

### Testando manualmente com cURL

**Login:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}'
```

**Listar livros (autenticado):**
```bash
curl -X GET http://localhost:8000/api/books \
  -H "Authorization: Bearer SEU_TOKEN_AQUI"
```

## Funcionalidades Automáticas

### Gerenciamento de Quantidade de Livros

A API gerencia automaticamente a quantidade disponível de livros:

- **Ao criar empréstimo:** Decrementa `quantidade_disponivel`
- **Ao registrar devolução:** Incrementa `quantidade_disponivel`
- **Validação:** Impede empréstimo se `quantidade_disponivel` < 1

### Validações

- CPF e email únicos para membros
- ISBN único para livros
- Data de devolução prevista deve ser após data de empréstimo
- Impede devolução duplicada (status já "devolvido")
- Valida existência de book_id e member_id nos empréstimos

## Estrutura do Projeto

```
biblioteca-api/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           ├── AuthController.php
│   │           ├── BookController.php
│   │           ├── MemberController.php
│   │           └── LoanController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Book.php
│   │   ├── Member.php
│   │   └── Loan.php
│   └── Traits/
│       └── ApiResponse.php
├── routes/
│   └── api.php
├── database/
│   └── migrations/
└── insomnia_collection.json
```

## Tecnologias Utilizadas

- **Laravel 9** - Framework PHP
- **Laravel Sanctum** - Autenticação via token
- **MySQL** - Banco de dados
- **Eloquent ORM** - Mapeamento objeto-relacional

## Banco de Dados

A API conecta-se ao banco de dados existente `biblioteca` que contém as seguintes tabelas:

- `users` - Usuários do sistema
- `books` - Catálogo de livros
- `members` - Membros da biblioteca
- `loans` - Registros de empréstimos
- `personal_access_tokens` - Tokens do Sanctum

## Suporte

Para reportar problemas ou sugerir melhorias, abra uma issue no repositório do projeto.

## Licença

Este projeto foi desenvolvido para fins educacionais.
