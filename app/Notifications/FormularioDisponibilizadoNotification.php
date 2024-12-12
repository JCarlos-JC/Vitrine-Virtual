<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Formulario;

class FormularioDisponibilizadoNotification extends Notification
{
    use Queueable;

    public $formulario;

    public function __construct(Formulario $formulario)
    {
        $this->formulario = $formulario;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('O formulário "' . $this->formulario->nome . '" foi disponibilizado.')
                    ->action('Ver Formulário', url('/formularios/' . $this->formulario->id))
                    ->line('Obrigado por usar nossa aplicação!');
    }

    public function toArray($notifiable)
    {
        return [
            'formulario_id' => $this->formulario->id,
            'titulo' => 'Formulário Disponibilizado',
            'mensagem' => 'O formulário "' . $this->formulario->nome . '" foi disponibilizado.'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'formulario_id' => $this->formulario->id,
            'titulo' => 'Formulário Disponibilizado',
            'mensagem' => 'O formulário "' . $this->formulario->nome . '" foi disponibilizado.'
        ]);
    }
}
