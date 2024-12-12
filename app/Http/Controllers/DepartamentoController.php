<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\departamento;



class DepartamentoController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $departamentos = Departamento::where('nome', 'like', '%' . $search . '%')->paginate(10);
        } else {
            $departamentos = Departamento::paginate(10);
        }

        return view('departamentos.index', ['departamentos' => $departamentos]);
    }

    public function search(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $departamentos = Departamento::where('nome', 'like', '%' . $search . '%')->paginate(10);
        } else {
            $departamentos = Departamento::paginate(10);
        }

        return view('departamentos.partials.table', ['departamentos' => $departamentos])->render();
    }


public function create(Request $request)
{
    // $faculdades = Faculdade::all(); // Recupere todas as faculdades
    return view('departamentos.create');
}


public function store(Request $request)
{
    Departamento::create($request->all());
    return redirect()->route('departamentos.index');
}

public function show($id)
{
    $departamento = Departamento::findOrFail($id);
    return view('departamentos.show', compact('departamento'));
}

public function edit($id)
{
    $departamento = Departamento::findOrFail($id);
    return view('departamentos.edit', compact('departamento'));
}


public function update(Request $request, $id)
{
    $departamento = Departamento::findOrFail($id);
    $departamento->update($request->all());
    return redirect()->route('departamentos.index');
}

public function destroy($id)
{
    $departamento = Departamento::findOrFail($id);
    $departamento->delete();
    return redirect()->route('departamentos.index');
}

}
