<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'endereco',
        'cpf',
        'data_nascimento',
        'categoria',
        'ativo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
        'data_cadastro' => 'datetime',
        'data_atualizacao' => 'datetime',
    ];

    /**
     * Custom timestamp column names
     */
    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_atualizacao';

    /**
     * Get the loans for the member.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class, 'member_id');
    }
}
