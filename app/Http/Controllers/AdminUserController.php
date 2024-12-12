<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Curso;

class AdminUserController extends Controller
{
    public function associarUsuariosCursos()
    {
        $users = User::all();
        $cursos = Curso::all();
        
        return view('admin.associar-usuarios-cursos', compact('users', 'cursos'));
    }

    public function salvarAssociacaoUsuariosCursos(Request $request)
    {
        // Valide os dados conforme necessário

        foreach ($request->usuarios as $userId => $cursoId) {
            $user = User::findOrFail($userId);
            $user->curso_id = $cursoId;
            $user->save();
        }

        return redirect()->route('admin.associar-usuarios-cursos')->with('success', 'Associações salvas com sucesso!');
    }
}
