<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComunicadoController extends Controller
{
public function index()
{
    // Obter o usuário autenticado
    $user = Auth::user();

    // Verificar se o usuário está autenticado
    if ($user) {
        // Obter os formulários disponíveis relacionados ao curso do usuário autenticado
        $formulariosDisponiveis = $user->cursos->flatMap->formularios;

        // Filtrar os formulários pauta pelo tipo de documento desejado e pelo curso do usuário autenticado
        $formulariosPauta = $formulariosDisponiveis->filter(function ($formulario) use ($user) {
            return $formulario->status 
                && $formulario->documento 
                && $formulario->documento->tipo_documento === 'Comunicado'
                && $formulario->curso_id === $user->curso_id;
        });
    } else {
        // Caso o usuário não esteja autenticado, inicialize $formulariosPauta como uma coleção vazia
        $formulariosPauta = collect([]);
    }

    // Passar os dados para a view
    return view('comunicado', compact('formulariosPauta'));
}


}
