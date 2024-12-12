<?php

namespace App\Listeners;

use App\Events\FormularioDisponibilizado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\FormularioDisponibilizadoEmail;

class EnviarNotificacaoFormularioDisponibilizado implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(FormularioDisponibilizado $event)
    {
        $formulario = $event->formulario;

        // Enviar email para todos os usuários
        $usuarios = \App\Models\User::all(); // Modifique para o seu modelo de usuário
        foreach ($usuarios as $usuario) {
            Mail::to($usuario->email)->send(new FormularioDisponibilizadoEmail($formulario));
        }
    }
}
