<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $books = Book::all();
        return $this->successResponse($books);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:200',
            'autor' => 'required|string|max:150',
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'editora' => 'required|string|max:100',
            'ano_publicacao' => 'required|integer|min:1000|max:' . date('Y'),
            'categoria' => 'required|string|max:50',
            'quantidade_total' => 'required|integer|min:1',
            'quantidade_disponivel' => 'required|integer|min:0',
            'localizacao' => 'required|string|max:50',
        ]);

        $book = Book::create($validated);

        return $this->successResponse($book, 'Livro criado com sucesso', 201);
    }

    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->notFoundResponse('Livro não encontrado');
        }

        return $this->successResponse($book);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->notFoundResponse('Livro não encontrado');
        }

        $validated = $request->validate([
            'titulo' => 'sometimes|string|max:200',
            'autor' => 'sometimes|string|max:150',
            'isbn' => 'sometimes|string|max:20|unique:books,isbn,' . $id,
            'editora' => 'sometimes|string|max:100',
            'ano_publicacao' => 'sometimes|integer|min:1000|max:' . date('Y'),
            'categoria' => 'sometimes|string|max:50',
            'quantidade_total' => 'sometimes|integer|min:1',
            'quantidade_disponivel' => 'sometimes|integer|min:0',
            'localizacao' => 'sometimes|string|max:50',
        ]);

        $book->update($validated);

        return $this->successResponse($book, 'Livro atualizado com sucesso');
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->notFoundResponse('Livro não encontrado');
        }

        $book->delete();

        return $this->successResponse(null, 'Livro excluído com sucesso');
    }
}
