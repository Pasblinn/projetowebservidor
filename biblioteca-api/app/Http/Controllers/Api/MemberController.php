<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $members = Member::all();
        return $this->successResponse($members);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:members,email',
            'telefone' => 'required|string|max:20',
            'endereco' => 'required|string|max:200',
            'cpf' => 'required|string|max:14|unique:members,cpf',
            'data_nascimento' => 'required|date',
            'categoria' => 'required|in:estudante,professor,comunidade',
            'ativo' => 'sometimes|boolean',
        ]);

        $member = Member::create($validated);

        return $this->successResponse($member, 'Membro criado com sucesso', 201);
    }

    public function show($id)
    {
        $member = Member::find($id);

        if (!$member) {
            return $this->notFoundResponse('Membro não encontrado');
        }

        return $this->successResponse($member);
    }

    public function update(Request $request, $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return $this->notFoundResponse('Membro não encontrado');
        }

        $validated = $request->validate([
            'nome' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:100|unique:members,email,' . $id,
            'telefone' => 'sometimes|string|max:20',
            'endereco' => 'sometimes|string|max:200',
            'cpf' => 'sometimes|string|max:14|unique:members,cpf,' . $id,
            'data_nascimento' => 'sometimes|date',
            'categoria' => 'sometimes|in:estudante,professor,comunidade',
            'ativo' => 'sometimes|boolean',
        ]);

        $member->update($validated);

        return $this->successResponse($member, 'Membro atualizado com sucesso');
    }

    public function destroy($id)
    {
        $member = Member::find($id);

        if (!$member) {
            return $this->notFoundResponse('Membro não encontrado');
        }

        $member->delete();

        return $this->successResponse(null, 'Membro excluído com sucesso');
    }
}
