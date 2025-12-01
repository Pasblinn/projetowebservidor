<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Book;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $loans = Loan::with(['book', 'member'])->get();
        return $this->successResponse($loans);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_id' => 'required|exists:books,id',
            'data_emprestimo' => 'required|date',
            'data_prevista_devolucao' => 'required|date|after:data_emprestimo',
            'observacoes' => 'nullable|string',
            'usuario_responsavel' => 'required|string|max:50',
        ]);

        $book = Book::find($validated['book_id']);

        if ($book->quantidade_disponivel < 1) {
            return $this->errorResponse('Livro não disponível para empréstimo', 400);
        }

        $validated['status'] = 'ativo';
        $loan = Loan::create($validated);

        $book->decrement('quantidade_disponivel');

        return $this->successResponse($loan->load(['book', 'member']), 'Empréstimo criado com sucesso', 201);
    }

    public function show($id)
    {
        $loan = Loan::with(['book', 'member'])->find($id);

        if (!$loan) {
            return $this->notFoundResponse('Empréstimo não encontrado');
        }

        return $this->successResponse($loan);
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return $this->notFoundResponse('Empréstimo não encontrado');
        }

        $validated = $request->validate([
            'data_devolucao' => 'required|date',
            'status' => 'required|in:devolvido',
            'observacoes' => 'nullable|string',
        ]);

        if ($loan->status === 'devolvido') {
            return $this->errorResponse('Este empréstimo já foi devolvido', 400);
        }

        $loan->update($validated);

        $book = Book::find($loan->book_id);
        $book->increment('quantidade_disponivel');

        return $this->successResponse($loan->load(['book', 'member']), 'Devolução registrada com sucesso');
    }

    public function destroy($id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return $this->notFoundResponse('Empréstimo não encontrado');
        }

        $loan->delete();

        return $this->successResponse(null, 'Empréstimo excluído com sucesso');
    }
}
