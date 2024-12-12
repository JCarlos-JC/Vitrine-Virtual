<?php

namespace App\Mail;

use App\Models\Midia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConteudoDisponibilizadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $midia;

    public function __construct(Midia $midia)
    {
        $this->midia = $midia;
    }

    public function build()
    {
        return $this->view('emails.conteudo_disponibilizado')
                    ->with([
                        'titulo' => $this->midia->titulo,
                        'descricao' => $this->midia->descricao,
                    ]);
    }
}
