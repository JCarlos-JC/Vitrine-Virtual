<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Curso;
use App\Models\Documento;
use App\Models\Departamento;
use App\Models\Formulario;
use App\Models\Midia;
use Auth;
use Carbon\Carbon; // Certifique-se de importar o Carbon

class DashboardController extends Controller
{
    public function index()
    { 
        $user = Auth::user();

        // Obtém os formulários disponíveis
        $formulariosDisponiveis = Formulario::all();

        // Calcula a contagem de pautas
        $pautasCount = $formulariosDisponiveis->filter(function ($formulario) {
            return $formulario->status && $formulario->documento && $formulario->documento->tipo_documento === 'Pauta';
        })->count();

        // Calcula a contagem de comunicados
        $comunicadosCount = $formulariosDisponiveis->filter(function ($formulario) {
            return $formulario->status && $formulario->documento && $formulario->documento->tipo_documento === 'Comunicado';
        })->count();

        // Calcula a contagem de declaração
        $declaracaoCount = $formulariosDisponiveis->filter(function ($formulario) {
            return $formulario->status && $formulario->documento && $formulario->documento->tipo_documento === 'Declaracao' && $formulario->curso->nome === 'Todos';
        })->count();

        // Calcula a contagem de horário
        $horarioCount = $formulariosDisponiveis->filter(function ($formulario) {
            return $formulario->status && $formulario->documento && $formulario->documento->tipo_documento === 'Horario';
        })->count();

        $usersCount = User::count();
        $newUsersToday = User::whereDate('created_at', Carbon::today())->count();
        $yesterdayUsers = User::whereDate('created_at', Carbon::yesterday())->count();
        $newUsersTodayPercentage = $yesterdayUsers > 0 ? (($newUsersToday - $yesterdayUsers) / $yesterdayUsers) * 100 : 0;
        $cursosCount = Curso::count();
        $documentosCount = Documento::count();
        $departamentosCount = Departamento::count();

        return view('dashboard.index', [
            'usersCount' => $usersCount,
            'cursosCount' => $cursosCount,
            'documentosCount' => $documentosCount,
            'departamentosCount' => $departamentosCount,
            'formulariosDisponiveis' => $formulariosDisponiveis,
            'pautasCount' => $pautasCount,
            'comunicadosCount' => $comunicadosCount,
            'declaracaoCount' => $declaracaoCount,
            'horarioCount' => $horarioCount,
            'newUsersToday' => $newUsersToday,
            'newUsersTodayPercentage' => $newUsersTodayPercentage, // Corrigido aqui
            'user' => $user // Adicionado aqui
        ]);
    }

    public function vizualiza()
    {
        $conteudos = Midia::all();

        return view('midias.midia', compact('conteudos'));
    }
}
