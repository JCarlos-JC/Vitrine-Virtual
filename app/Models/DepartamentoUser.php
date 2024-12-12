<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartamentoUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'curso_id'
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function Departamento()
        {
            return $this->belongsTo(Departamento::class);
        }
}
