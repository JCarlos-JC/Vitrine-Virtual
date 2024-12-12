<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class FormularioStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $formulario;

    public function __construct($formulario)
    {
        $this->formulario = $formulario;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'titulo' => 'Status do formulário alterado',
            'mensagem' => 'O formulário ' . $this->formulario->nome . ' teve seu status alterado para ' . ($this->formulario->status ? 'Disponível' : 'Indisponível') . '.',
            'formulario_id' => $this->formulario->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'titulo' => 'Status do formulário alterado',
                'mensagem' => 'O formulário ' . $this->formulario->nome . ' teve seu status alterado para ' . ($this->formulario->status ? 'Disponível' : 'Indisponível') . '.',
                'formulario_id' => $this->formulario->id,
            ],
        ]);
    }
}
