<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'curso_id',
        'departamento_id',
        'documento_id',
        'arquivo',
        'status',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
    public function parametro()
    {
        return $this->belongs(Parametro::class);
        
    }
}
