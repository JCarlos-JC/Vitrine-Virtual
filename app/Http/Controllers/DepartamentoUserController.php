<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartamentoUser;

class DepartamentoUserController extends Controller
{
    public function index()
    {
        $departamentoUsers = DepartamentoUser::all();
        return view('departamento_users.index', compact('departamentoUsers'));
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
        $departamentoUsers = DepartamentoUser::findOrFail($id);
        return view('departamento_users.show', compact('departamentoUsers'));
    }

    public function edit($id)
    {
        $departamentoUsers = DepartamentoUser::findOrFail($id);
        return view('departamento_users.edit', compact('departamentoUsers'));
    }

    public function update(Request $request, $id)
    {
        // Implemente conforme necessário
    }

    public function destroy($id)
    {
        $departamentoUsers = DepartamentoUser::findOrFail($id);
        $departamentoUsers->delete();
        return redirect()->route('departamento-user.index')->with('success', 'Departamento User deleted successfully');
    }
}
