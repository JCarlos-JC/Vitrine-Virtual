<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Formulario;
use App\Models\Curso;
use App\Models\Documento;
use App\Models\Departamento;
use Illuminate\Http\Response;
use App\Notifications\FormularioStatusChanged;
use App\Events\FormularioDisponibilizado;


use App\Notifications\FormularioDisponibilizadoNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;


class FormularioController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $formularios = Formulario::where('nome', 'like', '%' . $search . '%')->paginate(10);
        } else {
            $formularios = Formulario::paginate(10);
        }

        return view('formularios.index', ['formularios' => $formularios]);
    }

    public function search(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $formularios = Formulario::where('nome', 'like', '%' . $search . '%')->paginate(10);
        } else {
            $formularios = Formulario::paginate(10);
        }

        return view('formularios.partials.table', ['formularios' => $formularios])->render();
    }

    public function create()
    {
        $cursos = Curso::all();
        $documentos = Documento::all();
        $departamentos = Departamento::all();

        return view('formularios.create', compact('cursos', 'documentos', 'departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'curso_id' => 'required|exists:cursos,id',
            'departamento_id' => 'nullable|exists:departamentos,id', // Correção aqui
            'documento_id' => 'required|exists:documentos,id',
            'arquivo' => 'required|file',
            'status' => 'boolean',
        ]);

        $arquivoOriginalNome = $request->file('arquivo')->getClientOriginalName();
        $path = $request->file('arquivo')->storeAs('uploads', $arquivoOriginalNome);

        Formulario::create([
            'nome' => $request->nome,
            'curso_id' => $request->curso_id,
            'departamento_id' => $request->departamento_id, // Correção aqui
            'documento_id' => $request->documento_id,
            'arquivo' => $arquivoOriginalNome,
            'status' => $request->status,
        ]);

        return redirect()->route('formularios.index')->with('success', 'Formulário criado com sucesso!');
    }

    public function show(Formulario $formulario)
    {
        return view('formularios.show', compact('formulario'));
    }

    public function edit(Formulario $formulario)
    {
        $cursos = Curso::all();
        $documentos = Documento::all();
        $departamentos = Departamento::all();

        return view('formularios.edit', compact('formulario', 'cursos', 'documentos', 'departamentos'));
    }

    public function update(Request $request, Formulario $formulario)
    {
        $request->validate([
            'nome' => 'required',
            'curso_id' => 'required|exists:cursos,id',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'documento_id' => 'nullable|exists:documentos,id',
            'arquivo' => 'file',
            'status' => 'boolean',
        ]);

        $formulario->update($request->all());

        if ($request->hasFile('arquivo')) {
            $arquivoOriginalNome = $request->file('arquivo')->getClientOriginalName();
            $path = $request->file('arquivo')->storeAs('uploads', $arquivoOriginalNome);
            $formulario->arquivo = $arquivoOriginalNome;
            $formulario->save();
        }

        return redirect()->route('formularios.index')->with('success', 'Formulário atualizado com sucesso!');
    }


    public function destroy(Formulario $formulario)
    {
        $formulario->delete();
        return redirect()->route('formularios.index')->with('success', 'Formulário excluído com sucesso!');
    }

    public function download($id)
    {
        $formulario = Formulario::findOrFail($id);
        $filePath = storage_path('app/uploads/' . $formulario->arquivo);

        if (!Storage::exists('uploads/' . $formulario->arquivo)) {
            abort(404);
        }

        return response()->download($filePath);
    }


public function visualize($id)
{
    // Encontre o formulário com o ID fornecido
    $formulario = Formulario::findOrFail($id);

    // Caminho completo do arquivo
    $filePath = storage_path('app/uploads/' . $formulario->arquivo);

    // Verifique se o arquivo existe
    if (!Storage::exists('uploads/' . $formulario->arquivo)) {
        abort(404);
    }

    // Obtenha o conteúdo do arquivo
    $fileContents = Storage::get('uploads/' . $formulario->arquivo);

    // Obtenha o tipo MIME do arquivo
    $fileMimeType = Storage::mimeType('uploads/' . $formulario->arquivo);

    // Retorne a resposta com o conteúdo do arquivo e o tipo MIME
    return response($fileContents)
                ->header('Content-Type', $fileMimeType)
                ->header('Content-Disposition', 'inline; filename="' . $formulario->arquivo . '"');
}


public function changeStatus(Request $request, Formulario $formulario)
{
    $novoStatus = !$formulario->status; // Alterna o status
    $formulario->status = $novoStatus;
    $formulario->save();

    if ($novoStatus && $formulario->departamento) {
        $usuarios = $formulario->departamento->users;
        foreach ($usuarios as $usuario) {
            $usuario->notify(new FormularioStatusChanged($formulario));
        }
    }

    return response()->json([
        'status' => $novoStatus ? 'Disponível' : 'Indisponível',
    ]);
}


    public function indexDisponiveis()
    {

    $usuario = auth()->user(); // Obtém o usuário autenticado
    $departamentoDoUsuario = $usuario->departamentos()->first(); // Obtém o primeiro curso associado ao usuário
    if (!$departamentoDoUsuario) {
        // Trate aqui o caso em que o usuário não tem nenhum curso associado
        // Por exemplo, redirecionar para uma página de erro ou exibir uma mensagem
    }
    
    $formulariosDisponiveis = $departamentoDoUsuario->formularios()->where('status', true)->with('documento')->get();
    
    return view('formularios.ver.disponiveis', ['formulariosDisponiveis' => $formulariosDisponiveis, 'usuario' => $usuario]);
}
    

    public function indexHorario()
    {

         $usuario = auth()->user(); // Obtém o usuário autenticado
    $departamentoDoUsuario = $usuario->departamentos()->first(); // Obtém o primeiro curso associado ao usuário
    if (!$departamentoDoUsuario) {
        // Trate aqui o caso em que o usuário não tem nenhum curso associado
        // Por exemplo, redirecionar para uma página de erro ou exibir uma mensagem
    }
    
    $formulariosDisponiveis = $departamentoDoUsuario->formularios()->where('status', true)->with('documento')->get();
    
    return view('formularios.ver.horario', ['formulariosDisponiveis' => $formulariosDisponiveis, 'usuario' => $usuario]);
}
    

public function indexComunicado()
{
    $usuario = auth()->user(); // Obtém o usuário autenticado
    // $cursoDoUsuario = $usuario->cursos()->first(); // Obtém o primeiro curso associado ao usuário
    // if (!$cursoDoUsuario) {
    //     // Trate aqui o caso em que o usuário não tem nenhum curso associado
    //     // Por exemplo, redirecionar para uma página de erro ou exibir uma mensagem
    // }
    
    // $formulariosDisponiveis = $cursoDoUsuario->formularios()->where('status', true)->with('documento')->get();
    
        $formulariosDisponiveis = Formulario::where('status', true)->with('documento')->get();

    return view('formularios.ver.comunicado', ['formulariosDisponiveis' => $formulariosDisponiveis, 'usuario' => $usuario]);
}


public function indexDeclaracao()
{
    $usuario = auth()->user(); // Obtém o usuário autenticado
    $formulariosDisponiveis = Formulario::where('status', true)->with('documento')->get();

    return view('formularios.ver.declaracao', ['formulariosDisponiveis' => $formulariosDisponiveis, 'usuario' => $usuario]);

}

    

    public function searcher(Request $request)
    {
        $searcher = $request->input('search');

        $formulariosEncontrados = Formulario::where('nome', 'like', "%$searcher%")
            ->orWhereHas('curso', function ($query) use ($searcher) {
                $query->where('nome', 'like', "%$searcher%");
            })
            ->orWhereHas('departamento', function ($query) use ($searcher) {
                $query->where('nome', 'like', "%$searcher%");
            })
            ->orWhereHas('faculdade', function ($query) use ($searcher) {
                $query->where('nome', 'like', "%$search%");
            })
            ->get();

        // Você pode retornar a visualização parcial ou os dados em formato JSON, dependendo do que deseja fazer com os resultados da pesquisa.
        return view('formularios.ver.disponiveis', ['formulariosDisponiveis' => $formulariosDisponiveis]);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $formulario = Formulario::findOrFail($id);
            $formulario->status = $request->status;
            $formulario->save();

            if ($formulario->status) {
                // Disparar evento
                event(new FormularioDisponibilizado($formulario));

                // Notificar todos os usuários
                $usuarios = \App\Models\User::all();
                Notification::send($usuarios, new FormularioDisponibilizadoNotification($formulario));
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar status do formulário: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar status do formulário'], 500);
        }
    }
}




