<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConteudoDisponibilizado extends Notification
{
    use Queueable;

    protected $conteudo;

    public function __construct($conteudo)
    {
        $this->conteudo = $conteudo;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'titulo' => $this->conteudo->titulo,
            'descricao' => $this->conteudo->descricao,
            'arquivo' => $this->conteudo->arquivo,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'titulo' => $this->conteudo->titulo,
            'descricao' => $this->conteudo->descricao,
            'arquivo' => $this->conteudo->arquivo,
        ];
    }
}
