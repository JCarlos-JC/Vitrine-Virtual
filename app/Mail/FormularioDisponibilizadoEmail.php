<?php

namespace App\Mail;

use App\Models\Formulario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormularioDisponibilizadoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $formulario;

    public function __construct(Formulario $formulario)
    {
        $this->formulario = $formulario;
    }

    public function build()
    {
        return $this->view('emails.formulario_disponibilizado')
                    ->with(['formulario' => $this->formulario]);
    }
}
