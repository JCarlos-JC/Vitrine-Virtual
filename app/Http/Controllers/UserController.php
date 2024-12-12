<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use App\Models\Departamento;
use Carbon\Carbon;
use Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $users = User::where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->paginate(10);

        if ($request->ajax()) {
            return view('users.partials.table', compact('users'))->render();
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $departamentos = Departamento::all();
        $permissions = Permission::all();
        return view('users.create', compact('permissions', 'departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', // Adicionado confirmed para validar a confirmação da senha
            'departamento_id' => 'required', // Certifique-se de que departamento_id é obrigatório
            'permissions' => 'array' // Certifique-se de que permissions é um array
        ]);

        // Crie um novo usuário
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        $user->save();

        // Anexe permissões ao usuário
        if ($request->has('permissions')) {
            $user->permissions()->sync($request->input('permissions'));
        }

        // Associe o departamento ao usuário
        $user->departamentos()->attach($request->input('departamento_id'));

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departamentos = Departamento::all();
        $permissions = Permission::all();
        return view('users.edit', compact('user', 'departamentos', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Atualiza a relação de departamentos do usuário
        $user->departamentos()->sync([$request->input('departamento_id')]);

        // Atualiza as permissões do usuário
        if ($request->has('permissions')) {
            $user->permissions()->sync($request->input('permissions'));
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
