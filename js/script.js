/**
 * JavaScript personalizado para o Sistema de Gerenciamento de Biblioteca
 * Funcionalidades básicas para melhorar a experiência do usuário
 */

// Executa quando a página carrega completamente
document.addEventListener('DOMContentLoaded', function() {
    
    // Adiciona confirmação para botões de exclusão
    const deleteButtons = document.querySelectorAll('[onclick*="confirm"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Tem certeza que deseja excluir este item?')) {
                e.preventDefault(); // Cancela a ação se usuário clicar em "Cancelar"
            }
        });
    });
    
    // Auto-hide para alertas após 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000); // 5 segundos
    });
    
    // Adiciona classe 'fade-in' para animação suave
    document.body.classList.add('fade-in');
    
    // Validação simples nos formulários
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
            }
        });
    });
    
});

// Função para formatar CPF enquanto digita
function formatCPF(input) {
    let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito
    value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona primeiro ponto
    value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona segundo ponto
    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // Adiciona traço
    input.value = value;
}

// Função para formatar telefone
function formatPhone(input) {
    let value = input.value.replace(/\D/g, '');
    value = value.replace(/(\d{2})(\d)/, '($1) $2');
    value = value.replace(/(\d{4})(\d)/, '$1-$2');
    input.value = value;
}