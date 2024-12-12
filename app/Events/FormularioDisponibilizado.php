<?php

namespace App\Events;

use App\Models\Formulario;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FormularioDisponibilizado
{
    use Dispatchable, SerializesModels;

    public $formulario;

    public function __construct(Formulario $formulario)
    {
        $this->formulario = $formulario;
    }
}
