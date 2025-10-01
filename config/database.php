<?php
/**
 * CONFIGURAÇÃO DO SISTEMA DE BANCO DE DADOS SIMULADO
 * 
 * Este arquivo configura o sistema para usar a simulação de banco de dados
 * com arrays em memória, ideal para testes e desenvolvimento.
 * 
 * A simulação real está implementada em app/models/Database.php
 */

// Inclui a classe Database que contém a simulação com arrays
require_once __DIR__ . '/../app/models/Database.php';

/**
 * FUNÇÃO AUXILIAR GLOBAL PARA ACESSAR O BANCO DE DADOS SIMULADO
 * 
 * Função de conveniência que retorna a classe Database simulada.
 * Mantém compatibilidade com o código existente.
 * 
 * @return Database Classe Database com simulação em arrays
 */
function getDatabase() {
    return Database::class;
}