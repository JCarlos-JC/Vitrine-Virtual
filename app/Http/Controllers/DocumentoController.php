<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use App\Models\DepartamentoUser;
use Illuminate\Support\Facades\Validator;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $documentos = Documento::where('tipo', 'like', '%' . $search . '%')->paginate(10);
        } else {
            $documentos = Documento::paginate(10);
        }

        return view('documentos.index', ['documentos' => $documentos]);
    }

    public function search(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $documentos = Documento::where('tipo_documento', 'like', '%' . $search . '%')->paginate(10);
        } else {
            $documentos = Documento::paginate(10);
        }

        return view('documentos.partials.table', ['documentos' => $documentos])->render();
    }
    public function create()
    {
        return view('documentos.create');
    }

    public function store(Request $request)
    {
        // Validação dos dados recebidos do formulário
        $validator = Validator::make($request->all(), [
            'tipo_documento' => 'required|unique:documentos|max:255',
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Cria um novo documento com os dados do formulário
        Documento::create([
            'tipo_documento' => $request->input('tipo_documento'),
        ]);

        // Redireciona de volta para a página de criação com uma mensagem de sucesso
        return redirect()->route('documentos.index')->with('success', 'Documento adicionado com sucesso!');
    }

    public function show(Documento $documento)
    {
        return view('documentos.show', compact('documento'));
    }

    public function edit(Documento $documento)
    {
        return view('documentos.edit', compact('documento'));
    }

    public function update(Request $request, Documento $documento)
    {
        // Validação dos dados recebidos do formulário
        $request->validate([
            'tipo_documento' => 'required|unique:documentos,tipo_documento,'.$documento->id.'|max:255',
        ]);

        // Atualiza o documento com os dados do formulário
        $documento->update([
            'tipo_documento' => $request->input('tipo_documento'),
        ]);

        // Redireciona de volta para a página de índice com uma mensagem de sucesso
        return redirect()->route('documentos.index')->with('success', 'Documento atualizado com sucesso!');
    }

    public function destroy(Documento $documento)
    {
        // Exclui o documento
        $documento->delete();

        // Redireciona de volta para a página de índice com uma mensagem de sucesso
        return redirect()->route('documentos.index')->with('success', 'Documento excluído com sucesso!');
    }
}
