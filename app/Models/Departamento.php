<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;
    protected $fillable = ['nome'];

    //    public function faculdade()
    // {
    //     return $this->belongsTo(Faculdade::class);
    // }

            public function users()
    {
        return $this->hasMany(User::class);
    }

    public function formularios()
    {
        return $this->hasMany(Formulario::class);
    }
}

