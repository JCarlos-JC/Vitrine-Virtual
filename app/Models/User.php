<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Curso;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',

    
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
        /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $dates = [
        'last_seen',
    ];

    public function departamentos()
    {
        return $this->belongsToMany(Departamento::class);
    }

     public function isGestor()
    {
        // Aqui você implementa a lógica para determinar se o usuário é um gestor
        // Por exemplo, se o usuário tiver um papel de gestor atribuído, você pode fazer algo assim:
        return $this->papel === 'gestor'; // Supondo que você tenha um campo 'papel' na tabela de usuários
    }
}
