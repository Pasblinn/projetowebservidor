# 🚀 GUIA DE INSTALAÇÃO - XAMPP WINDOWS

## ✅ **CONFIGURAÇÃO SUPER SIMPLES**

### **1. PRÉ-REQUISITO:**
- XAMPP já instalado com Apache funcionando
- Só isso! Nada mais precisa instalar.

### **2. COLOCAR O PROJETO:**
- Copie a pasta `projetoweb-servidor` para `C:\xampp\htdocs\`
- Estrutura final: `C:\xampp\htdocs\projetoweb-servidor\`

### **3. SÓ ISSO:**
- Abra Apache no XAMPP
- Pronto! Não precisa configurar nada

### **4. ACESSAR O SISTEMA:**

**URL:** `http://localhost/projetoweb-servidor/`

**Usuários para teste:**
- **Admin:** `admin` / `password`
- **Bibliotecário:** `bibliotecario` / `password`

### **6. FUNCIONALIDADES DISPONÍVEIS:**
- 🔐 Login/Logout com validação PHP
- 📚 CRUD completo de Livros
- 👥 CRUD completo de Membros  
- 📊 Dashboard com estatísticas
- 💻 Interface responsiva (Bootstrap)
- ✅ Validações PHP no servidor
- 📝 Mensagens de feedback
- 🎨 Design moderno

### **7. ESTRUTURA SIMPLES:**
```
projetoweb-servidor/
├── index.php          # Entrada principal
├── .htaccess          # Configuração Apache
├── css/style.css      # Estilos
├── js/script.js       # JavaScript
├── config/config.php  # Configurações
├── app/
│   ├── controllers/   # Lógica do sistema
│   ├── models/       # Dados (arrays)
│   └── views/        # Interface HTML
└── README.md         # Documentação
```

### **8. ARRAYS QUE SIMULAM BANCO:**
- **Users:** Usuários do sistema
- **Books:** Livros da biblioteca  
- **Members:** Membros cadastrados
- **Loans:** Empréstimos (preparado)

### **🔥 TESTE RÁPIDO:**
1. Abra: `http://localhost/projetoweb-servidor/`
2. Login: `admin` / `password`
3. Vá em "Livros" → "Novo Livro"
4. Cadastre um livro de teste
5. Veja a lista atualizada

### **❌ SEM BANCO DE DADOS:**
- Não precisa MySQL/MariaDB
- Não precisa configurar database
- Tudo funciona com arrays PHP
- Dados são simulados na memória

### **🛠️ TROUBLESHOOTING:**
- **Erro 404?** Verifique mod_rewrite
- **Não carrega CSS?** Confirme BASE_URL
- **Erro PHP?** Verifique se é PHP 8+
- **Não redireciona?** Veja .htaccess

## ✨ **SISTEMA 100% FUNCIONAL E PRONTO!**