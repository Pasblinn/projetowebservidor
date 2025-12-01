<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'autor',
        'isbn',
        'editora',
        'ano_publicacao',
        'categoria',
        'quantidade_total',
        'quantidade_disponivel',
        'localizacao',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ano_publicacao' => 'integer',
        'quantidade_total' => 'integer',
        'quantidade_disponivel' => 'integer',
        'data_cadastro' => 'datetime',
        'data_atualizacao' => 'datetime',
    ];

    /**
     * Custom timestamp column names
     */
    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_atualizacao';

    /**
     * Get the loans for the book.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class, 'book_id');
    }
}
