<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoUser extends Model
{
    use HasFactory;

    protected $table = 'curso_user';

    protected $fillable = [
        'user_id',
        'curso_id'
    ];

    // Relação com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relação com o modelo Curso
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
