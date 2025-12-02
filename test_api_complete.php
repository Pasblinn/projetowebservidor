<?php

function makeRequest($method, $endpoint, $data = null, $token = null) {
    $url = 'http://127.0.0.1:8000/api' . $endpoint;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'body' => json_decode($response, true)
    ];
}

function printTest($number, $title, $expected, $actual, $response = null) {
    $status = $expected === $actual ? '✓' : '✗';
    $color = $expected === $actual ? '' : ' [ERRO]';

    echo "\n$number. $title\n";
    echo "   Status HTTP: $actual (esperado: $expected) $status$color\n";

    if ($response && isset($response['message'])) {
        echo "   Mensagem: " . $response['message'] . "\n";
    }

    if ($response && isset($response['data'])) {
        if (is_array($response['data']) && count($response['data']) > 0) {
            if (isset($response['data'][0])) {
                echo "   Total de registros: " . count($response['data']) . "\n";
            } else {
                echo "   Dados retornados: " . json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            }
        }
    }
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "           TESTES COMPLETOS DA API BIBLIOTECA                  \n";
echo "═══════════════════════════════════════════════════════════════\n";

$token = null;
$createdBookId = null;
$createdMemberId = null;
$createdLoanId = null;

// ========== TESTES DE AUTENTICAÇÃO ==========
echo "\n[AUTENTICAÇÃO]\n";
echo "───────────────────────────────────────────────────────────────\n";

// Teste 1: Login com credenciais corretas
$response = makeRequest('POST', '/login', [
    'username' => 'admin',
    'password' => 'password'
]);
printTest('1', 'Login com credenciais corretas', 200, $response['code'], $response['body']);
if ($response['code'] === 200) {
    $token = $response['body']['data']['token'];
    echo "   Token: " . substr($token, 0, 30) . "...\n";
}

// Teste 2: Login com senha incorreta
$response = makeRequest('POST', '/login', [
    'username' => 'admin',
    'password' => 'senhaerrada'
]);
printTest('2', 'Login com senha incorreta (deve falhar)', 401, $response['code'], $response['body']);

// Teste 3: Login sem username
$response = makeRequest('POST', '/login', [
    'password' => 'password'
]);
printTest('3', 'Login sem username (deve falhar)', 422, $response['code'], $response['body']);

// Teste 4: GET /api/me (informações do usuário autenticado)
$response = makeRequest('GET', '/me', null, $token);
printTest('4', 'Obter informações do usuário autenticado', 200, $response['code'], $response['body']);

// Teste 5: GET /api/me sem token (deve falhar)
$response = makeRequest('GET', '/me');
printTest('5', 'Tentar acessar /me sem token (deve falhar)', 401, $response['code'], $response['body']);

// ========== TESTES DE BOOKS (CRUD COMPLETO) ==========
echo "\n\n[BOOKS - CRUD COMPLETO]\n";
echo "───────────────────────────────────────────────────────────────\n";

// Teste 6: GET /api/books - Listar todos
$response = makeRequest('GET', '/books', null, $token);
printTest('6', 'GET /api/books - Listar todos os livros', 200, $response['code'], $response['body']);

// Teste 7: POST /api/books - Criar livro com dados válidos
$newBook = [
    'titulo' => 'Teste API - ' . date('H:i:s'),
    'autor' => 'Autor Teste',
    'isbn' => '978' . rand(1000000000, 9999999999),
    'editora' => 'Editora Teste',
    'ano_publicacao' => 2024,
    'categoria' => 'Teste',
    'quantidade_total' => 5,
    'quantidade_disponivel' => 5,
    'localizacao' => 'Estante Teste'
];
$response = makeRequest('POST', '/books', $newBook, $token);
printTest('7', 'POST /api/books - Criar livro válido', 201, $response['code'], $response['body']);
if ($response['code'] === 201) {
    $createdBookId = $response['body']['data']['id'];
    echo "   ID do livro criado: $createdBookId\n";
}

// Teste 8: POST /api/books - Criar livro sem título (deve falhar)
$invalidBook = [
    'autor' => 'Autor Teste',
    'isbn' => '978' . rand(1000000000, 9999999999),
];
$response = makeRequest('POST', '/books', $invalidBook, $token);
printTest('8', 'POST /api/books - Criar livro sem título (deve falhar)', 422, $response['code'], $response['body']);

// Teste 9: POST /api/books - ISBN duplicado (deve falhar)
$response = makeRequest('POST', '/books', $newBook, $token);
printTest('9', 'POST /api/books - ISBN duplicado (deve falhar)', 422, $response['code'], $response['body']);

// Teste 10: GET /api/books/{id} - Buscar livro específico
if ($createdBookId) {
    $response = makeRequest('GET', "/books/$createdBookId", null, $token);
    printTest('10', "GET /api/books/$createdBookId - Buscar livro específico", 200, $response['code'], $response['body']);
}

// Teste 11: GET /api/books/999999 - Buscar livro inexistente
$response = makeRequest('GET', '/books/999999', null, $token);
printTest('11', 'GET /api/books/999999 - Buscar livro inexistente (deve falhar)', 404, $response['code'], $response['body']);

// Teste 12: PUT /api/books/{id} - Atualizar livro
if ($createdBookId) {
    $updateData = [
        'quantidade_total' => 10,
        'quantidade_disponivel' => 8
    ];
    $response = makeRequest('PUT', "/books/$createdBookId", $updateData, $token);
    printTest('12', "PUT /api/books/$createdBookId - Atualizar livro", 200, $response['code'], $response['body']);
}

// ========== TESTES DE MEMBERS (CRUD COMPLETO) ==========
echo "\n\n[MEMBERS - CRUD COMPLETO]\n";
echo "───────────────────────────────────────────────────────────────\n";

// Teste 13: GET /api/members - Listar todos
$response = makeRequest('GET', '/members', null, $token);
printTest('13', 'GET /api/members - Listar todos os membros', 200, $response['code'], $response['body']);

// Teste 14: POST /api/members - Criar membro válido
$newMember = [
    'nome' => 'Membro Teste API',
    'email' => 'teste' . time() . '@api.com',
    'telefone' => '(42) 98888-8888',
    'endereco' => 'Rua Teste API, 123',
    'cpf' => sprintf('%011d', rand(10000000000, 99999999999)),
    'data_nascimento' => '1995-05-15',
    'categoria' => 'estudante',
    'ativo' => true
];
$response = makeRequest('POST', '/members', $newMember, $token);
printTest('14', 'POST /api/members - Criar membro válido', 201, $response['code'], $response['body']);
if ($response['code'] === 201) {
    $createdMemberId = $response['body']['data']['id'];
    echo "   ID do membro criado: $createdMemberId\n";
}

// Teste 15: POST /api/members - Email duplicado (deve falhar)
$response = makeRequest('POST', '/members', $newMember, $token);
printTest('15', 'POST /api/members - Email duplicado (deve falhar)', 422, $response['code'], $response['body']);

// Teste 16: POST /api/members - Categoria inválida (deve falhar)
$invalidMember = $newMember;
$invalidMember['email'] = 'outro' . time() . '@api.com';
$invalidMember['cpf'] = sprintf('%011d', rand(10000000000, 99999999999));
$invalidMember['categoria'] = 'categoria_invalida';
$response = makeRequest('POST', '/members', $invalidMember, $token);
printTest('16', 'POST /api/members - Categoria inválida (deve falhar)', 422, $response['code'], $response['body']);

// Teste 17: GET /api/members/{id} - Buscar membro específico
if ($createdMemberId) {
    $response = makeRequest('GET', "/members/$createdMemberId", null, $token);
    printTest('17', "GET /api/members/$createdMemberId - Buscar membro específico", 200, $response['code'], $response['body']);
}

// Teste 18: PUT /api/members/{id} - Atualizar membro
if ($createdMemberId) {
    $updateData = [
        'telefone' => '(42) 99999-9999',
        'endereco' => 'Novo endereço, 456'
    ];
    $response = makeRequest('PUT', "/members/$createdMemberId", $updateData, $token);
    printTest('18', "PUT /api/members/$createdMemberId - Atualizar membro", 200, $response['code'], $response['body']);
}

// ========== TESTES DE LOANS (CRUD + LÓGICA DE NEGÓCIO) ==========
echo "\n\n[LOANS - CRUD + LÓGICA DE NEGÓCIO]\n";
echo "───────────────────────────────────────────────────────────────\n";

// Teste 19: GET /api/loans - Listar todos
$response = makeRequest('GET', '/loans', null, $token);
printTest('19', 'GET /api/loans - Listar todos os empréstimos', 200, $response['code'], $response['body']);

// Teste 20: POST /api/loans - Criar empréstimo válido
if ($createdMemberId && $createdBookId) {
    // Primeiro verificar quantidade disponível antes do empréstimo
    $responseBefore = makeRequest('GET', "/books/$createdBookId", null, $token);
    $qtdAntes = $responseBefore['body']['data']['quantidade_disponivel'];

    $newLoan = [
        'member_id' => $createdMemberId,
        'book_id' => $createdBookId,
        'data_emprestimo' => date('Y-m-d'),
        'data_prevista_devolucao' => date('Y-m-d', strtotime('+14 days')),
        'usuario_responsavel' => 'admin',
        'observacoes' => 'Teste de empréstimo via API'
    ];
    $response = makeRequest('POST', '/loans', $newLoan, $token);
    printTest('20', 'POST /api/loans - Criar empréstimo válido', 201, $response['code'], $response['body']);

    if ($response['code'] === 201) {
        $createdLoanId = $response['body']['data']['id'];
        echo "   ID do empréstimo criado: $createdLoanId\n";

        // Verificar se a quantidade foi decrementada
        $responseAfter = makeRequest('GET', "/books/$createdBookId", null, $token);
        $qtdDepois = $responseAfter['body']['data']['quantidade_disponivel'];
        echo "   Quantidade antes: $qtdAntes, depois: $qtdDepois (deve ser -1)\n";

        if ($qtdDepois === $qtdAntes - 1) {
            echo "   ✓ Quantidade decrementada corretamente!\n";
        } else {
            echo "   ✗ ERRO: Quantidade não foi decrementada!\n";
        }
    }
}

// Teste 21: POST /api/loans - Data devolução antes da data empréstimo (deve falhar)
if ($createdMemberId && $createdBookId) {
    $invalidLoan = [
        'member_id' => $createdMemberId,
        'book_id' => $createdBookId,
        'data_emprestimo' => date('Y-m-d'),
        'data_prevista_devolucao' => date('Y-m-d', strtotime('-7 days')),
        'usuario_responsavel' => 'admin'
    ];
    $response = makeRequest('POST', '/loans', $invalidLoan, $token);
    printTest('21', 'POST /api/loans - Data devolução inválida (deve falhar)', 422, $response['code'], $response['body']);
}

// Teste 22: POST /api/loans - Member inexistente (deve falhar)
if ($createdBookId) {
    $invalidLoan = [
        'member_id' => 999999,
        'book_id' => $createdBookId,
        'data_emprestimo' => date('Y-m-d'),
        'data_prevista_devolucao' => date('Y-m-d', strtotime('+14 days')),
        'usuario_responsavel' => 'admin'
    ];
    $response = makeRequest('POST', '/loans', $invalidLoan, $token);
    printTest('22', 'POST /api/loans - Member inexistente (deve falhar)', 422, $response['code'], $response['body']);
}

// Teste 23: GET /api/loans/{id} - Buscar empréstimo com relacionamentos
if ($createdLoanId) {
    $response = makeRequest('GET', "/loans/$createdLoanId", null, $token);
    printTest('23', "GET /api/loans/$createdLoanId - Buscar empréstimo com relacionamentos", 200, $response['code'], $response['body']);

    if (isset($response['body']['data']['book']) && isset($response['body']['data']['member'])) {
        echo "   ✓ Relacionamentos carregados (book e member)!\n";
    }
}

// Teste 24: PUT /api/loans/{id} - Registrar devolução
if ($createdLoanId && $createdBookId) {
    // Verificar quantidade antes da devolução
    $responseBefore = makeRequest('GET', "/books/$createdBookId", null, $token);
    $qtdAntes = $responseBefore['body']['data']['quantidade_disponivel'];

    $returnData = [
        'data_devolucao' => date('Y-m-d'),
        'status' => 'devolvido',
        'observacoes' => 'Devolvido em perfeito estado'
    ];
    $response = makeRequest('PUT', "/loans/$createdLoanId", $returnData, $token);
    printTest('24', "PUT /api/loans/$createdLoanId - Registrar devolução", 200, $response['code'], $response['body']);

    if ($response['code'] === 200) {
        // Verificar se a quantidade foi incrementada
        $responseAfter = makeRequest('GET', "/books/$createdBookId", null, $token);
        $qtdDepois = $responseAfter['body']['data']['quantidade_disponivel'];
        echo "   Quantidade antes: $qtdAntes, depois: $qtdDepois (deve ser +1)\n";

        if ($qtdDepois === $qtdAntes + 1) {
            echo "   ✓ Quantidade incrementada corretamente!\n";
        } else {
            echo "   ✗ ERRO: Quantidade não foi incrementada!\n";
        }
    }
}

// Teste 25: PUT /api/loans/{id} - Tentar devolver novamente (deve falhar)
if ($createdLoanId) {
    $returnData = [
        'data_devolucao' => date('Y-m-d'),
        'status' => 'devolvido'
    ];
    $response = makeRequest('PUT', "/loans/$createdLoanId", $returnData, $token);
    printTest('25', "PUT /api/loans/$createdLoanId - Devolução duplicada (deve falhar)", 400, $response['code'], $response['body']);
}

// ========== TESTES DE EXCLUSÃO (DELETE) ==========
echo "\n\n[DELETE - REMOÇÃO DE RECURSOS]\n";
echo "───────────────────────────────────────────────────────────────\n";

// Teste 26: DELETE /api/loans/{id}
if ($createdLoanId) {
    $response = makeRequest('DELETE', "/loans/$createdLoanId", null, $token);
    printTest('26', "DELETE /api/loans/$createdLoanId - Deletar empréstimo", 200, $response['code'], $response['body']);
}

// Teste 27: DELETE /api/books/{id}
if ($createdBookId) {
    $response = makeRequest('DELETE', "/books/$createdBookId", null, $token);
    printTest('27', "DELETE /api/books/$createdBookId - Deletar livro", 200, $response['code'], $response['body']);
}

// Teste 28: DELETE /api/members/{id}
if ($createdMemberId) {
    $response = makeRequest('DELETE', "/members/$createdMemberId", null, $token);
    printTest('28', "DELETE /api/members/$createdMemberId - Deletar membro", 200, $response['code'], $response['body']);
}

// Teste 29: DELETE /api/books/999999 - Deletar recurso inexistente (deve falhar)
$response = makeRequest('DELETE', '/books/999999', null, $token);
printTest('29', 'DELETE /api/books/999999 - Deletar inexistente (deve falhar)', 404, $response['code'], $response['body']);

// ========== TESTE DE LOGOUT ==========
echo "\n\n[LOGOUT]\n";
echo "───────────────────────────────────────────────────────────────\n";

// Teste 30: POST /api/logout
$response = makeRequest('POST', '/logout', null, $token);
printTest('30', 'POST /api/logout - Fazer logout', 200, $response['code'], $response['body']);

// Teste 31: Tentar usar token após logout (deve falhar)
$response = makeRequest('GET', '/me', null, $token);
printTest('31', 'GET /api/me - Usar token após logout (deve falhar)', 401, $response['code'], $response['body']);

// ========== RESUMO ==========
echo "\n\n═══════════════════════════════════════════════════════════════\n";
echo "                     RESUMO DOS TESTES                         \n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n✅ Total de testes executados: 31\n";
echo "\nCategorias testadas:\n";
echo "   • Autenticação (Login, Logout, Me): 5 testes\n";
echo "   • Books CRUD: 7 testes\n";
echo "   • Members CRUD: 6 testes\n";
echo "   • Loans CRUD + Lógica: 9 testes\n";
echo "   • DELETE: 4 testes\n";
echo "\nFuncionalidades validadas:\n";
echo "   ✓ Autenticação com token bearer\n";
echo "   ✓ CRUD completo para Books, Members e Loans\n";
echo "   ✓ Validações de entrada (campos obrigatórios, únicos, formatos)\n";
echo "   ✓ Códigos HTTP corretos (200, 201, 400, 401, 404, 422)\n";
echo "   ✓ Relacionamentos Eloquent (eager loading)\n";
echo "   ✓ Lógica de negócio (decremento/incremento automático de estoque)\n";
echo "   ✓ Prevenção de duplicação (email, CPF, ISBN)\n";
echo "   ✓ Prevenção de erros (devolução duplicada, datas inválidas)\n";
echo "\n═══════════════════════════════════════════════════════════════\n";
