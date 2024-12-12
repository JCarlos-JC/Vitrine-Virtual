<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabalho extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'autor',
        'titulo',
        'orientador',
        'resumo',
        'abstract',
        'palavras_chave',
        'idioma',
        'pais',
        'instituicao',
        'departamento',
        'uri',
        'data_documento',
        'descricao',
        'arquivo'
    ];

    protected $dates = ['data_documento'];
}
