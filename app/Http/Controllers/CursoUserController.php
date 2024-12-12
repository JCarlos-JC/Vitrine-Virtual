<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CursoUser;

class CursoUserController extends Controller
{
    public function index()
    {
        $cursoUsers = CursoUser::all();
        return view('curso_users.index', compact('cursoUsers'));
    }

    public function create()
    {
        // Implemente conforme necessário
    }

    public function store(Request $request)
    {
        // Implemente conforme necessário
    }

    public function show($id)
    {
        $cursoUser = CursoUser::findOrFail($id);
        return view('curso_users.show', compact('cursoUser'));
    }

    public function edit($id)
    {
        $cursoUser = CursoUser::findOrFail($id);
        return view('curso_users.edit', compact('cursoUser'));
    }

    public function update(Request $request, $id)
    {
        // Implemente conforme necessário
    }

    public function destroy($id)
    {
        $cursoUser = CursoUser::findOrFail($id);
        $cursoUser->delete();
        return redirect()->route('curso-user.index')->with('success', 'Curso User deleted successfully');
    }
}
