<?php
echo "=================================\n";
echo "TESTE DA API BIBLIOTECA\n";
echo "=================================\n\n";

// Teste 1: Login
echo "1. Fazendo login...\n";
$ch = curl_init('http://localhost/projetowebservidor-main/biblioteca-api/public/api/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['username' => 'admin', 'password' => 'password']));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    $token = $data['data']['token'];
    echo "   ✓ Login realizado com sucesso!\n";
    echo "   Token: " . substr($token, 0, 20) . "...\n\n";

    // Teste 2: Listar livros
    echo "2. Listando livros...\n";
    $ch = curl_init('http://localhost/projetowebservidor-main/biblioteca-api/public/api/books');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $data = json_decode($response, true);
        echo "   ✓ " . count($data['data']) . " livros encontrados\n\n";
    } else {
        echo "   ✗ Erro ao listar livros (Status: $httpCode)\n\n";
    }

    // Teste 3: Listar membros
    echo "3. Listando membros...\n";
    $ch = curl_init('http://localhost/projetowebservidor-main/biblioteca-api/public/api/members');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $data = json_decode($response, true);
        echo "   ✓ " . count($data['data']) . " membros encontrados\n\n";
    } else {
        echo "   ✗ Erro ao listar membros (Status: $httpCode)\n\n";
    }

    // Teste 4: Listar empréstimos
    echo "4. Listando empréstimos...\n";
    $ch = curl_init('http://localhost/projetowebservidor-main/biblioteca-api/public/api/loans');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $data = json_decode($response, true);
        echo "   ✓ " . count($data['data']) . " empréstimos encontrados\n\n";
    } else {
        echo "   ✗ Erro ao listar empréstimos (Status: $httpCode)\n\n";
    }

    // Teste 5: Obter informações do usuário
    echo "5. Obtendo informações do usuário logado...\n";
    $ch = curl_init('http://localhost/projetowebservidor-main/biblioteca-api/public/api/me');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $data = json_decode($response, true);
        echo "   ✓ Usuário: " . $data['data']['nome'] . " (" . $data['data']['tipo'] . ")\n\n";
    } else {
        echo "   ✗ Erro ao obter informações do usuário (Status: $httpCode)\n\n";
    }

    echo "=================================\n";
    echo "TODOS OS TESTES CONCLUÍDOS!\n";
    echo "=================================\n";

} else {
    echo "   ✗ Falha no login! (Status: $httpCode)\n";
    echo "   Resposta: $response\n";
}
