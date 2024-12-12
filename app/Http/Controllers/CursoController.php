<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Faculdade;

use Illuminate\Http\Request;

class CursoController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $cursos = Curso::where('nome', 'like', '%' . $search . '%')->paginate(10);
        } else {
            $cursos = Curso::paginate(10);
        }

        return view('cursos.index', ['cursos' => $cursos]);
    }

    public function search(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $cursos = Curso::where('nome', 'like', '%' . $search . '%')->paginate(10);
        } else {
            $cursos = Curso::paginate(10);
        }

        return view('cursos.partials.table', ['cursos' => $cursos])->render();
    }


public function create()
{

    // $faculdades = Faculdade::all(); // Recupere todas as faculdades
    return view('cursos.create');
}

public function store(Request $request)
{
    Curso::create($request->all());
    return redirect()->route('cursos.index');
}

public function show($id)
{
    $curso = Curso::findOrFail($id);
    return view('cursos.show', compact('curso'));
}

public function edit($id)
{
    $curso = Curso::findOrFail($id);

    return view('cursos.edit', compact('curso'));
}


public function update(Request $request, $id)
{
    $curso = Curso::findOrFail($id);
    $curso->update($request->all());
    return redirect()->route('cursos.index');
}

public function destroy($id)
{
    $curso = Curso::findOrFail($id);
    $curso->delete();
    return redirect()->route('cursos.index');
}

}
