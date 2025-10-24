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

### Ferramentas
- Composer (gerenciador de dependências do PHP)
- Apache (servidor web)
- PDO (para conectar com o banco de forma segura)

## Estrutura do Código

Organizamos o código seguindo o padrão MVC (Model-View-Controller):

```
app/
├── Controllers/     # Lógica de controle (o que acontece quando clica em algo)
├── Models/
│   ├── Entities/    # Representam objetos (Livro, Membro, etc)
│   └── Repositories/# Fazem as consultas no banco
├── Core/            # Classes principais (Router, Database)
├── views/           # Páginas HTML que o usuário vê
└── Config/          # Configurações e rotas

database/
├── schema.sql       # Script que cria as tabelas
└── seed.sql         # Dados de exemplo para testar
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

## Instalação

**Requisitos:**
- PHP 8.0 ou superior
- MySQL
- Apache com mod_rewrite
- Composer

**Passos:**

1. Clone o repositório
```bash
git clone https://github.com/Pasblinn/projetowebservidor.git
```

2. Instale as dependências
```bash
composer install
```

3. Configure o banco de dados
- Crie um banco chamado `biblioteca`
- Execute o arquivo `database/schema.sql` para criar as tabelas
- Execute `database/seed.sql` para adicionar dados de exemplo

4. Configure o arquivo `.env`
- Copie `.env.example` para `.env`
- Ajuste as configurações de banco (host, usuário, senha)
- Ajuste o `BASE_PATH` de acordo com onde você colocou o projeto

5. Acesse no navegador
```
http://localhost/projetowebservidor/
```

**Login padrão:** usuário `admin`, senha `password`

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
- PHP 8+ com orientação a objetos
- Composer com autoload (PSR-4)
- Banco de dados MySQL via PDO
- Sistema de rotas (URLs transparentes)
- Padrão MVC
- Validações no servidor
- Interface adequada com feedback visual

Tentamos seguir boas práticas de desenvolvimento, separando bem as responsabilidades de cada parte do código e pensando em segurança (principalmente contra SQL injection e XSS).

## Melhorias ao Longo do Desenvolvimento

Durante o desenvolvimento, fomos evoluindo o projeto:

- Começamos guardando dados em arrays de sessão, depois migramos para banco de dados real
- Melhoramos a organização do código, separando em camadas (MVC + Repository)
- Adicionamos o Composer para gerenciar dependências
- Criamos um sistema de rotas próprio para URLs mais limpas
- Aplicamos tipagem forte nas classes para aproveitar recursos do PHP 8
- Melhoramos a interface usando Bootstrap

## Repositório

https://github.com/Pasblinn/projetowebservidor

---

**Sistema de Gerenciamento de Biblioteca - 2025**
