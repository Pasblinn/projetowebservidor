<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id',
        'book_id',
        'data_emprestimo',
        'data_prevista_devolucao',
        'data_devolucao',
        'status',
        'observacoes',
        'usuario_responsavel',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_emprestimo' => 'date',
        'data_prevista_devolucao' => 'date',
        'data_devolucao' => 'date',
        'data_criacao' => 'datetime',
        'data_atualizacao' => 'datetime',
    ];

    /**
     * Custom timestamp column names
     */
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';

    /**
     * Get the book that was loaned.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    /**
     * Get the member that borrowed the book.
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
