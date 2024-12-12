<?php

namespace App\Http\Controllers;

use App\Models\Trabalho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TrabalhoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trabalhos = Trabalho::paginate(10);
        return view('trabalhos.index', compact('trabalhos'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $trabalhos = Trabalho::where('tipo', 'LIKE', "%{$search}%")
                    ->orWhere('autor', 'LIKE', "%{$search}%")
                    ->paginate(10);

        if ($request->ajax()) {
            return view('trabalhos.partials.table', compact('trabalhos'))->render();
        }

        return view('trabalhos.index', compact('trabalhos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trabalhos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateData($request);

        DB::beginTransaction();
        try {
            if ($request->hasFile('arquivo')) {
                $validatedData['arquivo'] = $this->uploadFile($request->file('arquivo'));
            }

            Trabalho::create($validatedData);
            DB::commit();

            return redirect()->route('trabalhos.index')->with('success', 'Trabalho criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Erro ao criar trabalho: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trabalho $trabalho)
    {
        return view('trabalhos.edit', compact('trabalho'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Trabalho $trabalho)
{
    // Validação dos dados
    $validatedData = $request->validate([
        'tipo' => 'required|string|max:255',
        'autor' => 'required|string|max:255',
        'titulo' => 'required|string|max:255',
        'orientador' => 'required|string|max:255',
        'resumo' => 'required',
        'abstract' => 'required',
        'palavras_chave' => 'required|string|max:255',
        'idioma' => 'required|string|max:255',
        'pais' => 'required|string|max:255',
        'instituicao' => 'required|string|max:255',
        'departamento' => 'required|string|max:255',
        'uri' => 'required|string|max:255',
        'data_documento' => 'required|date',
        'arquivo' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Validação para o arquivo
        'descricao' => 'nullable|string',
    ]);

    // Verificar se um novo arquivo foi enviado
    if ($request->hasFile('arquivo')) {
        // Deletar o arquivo anterior, se existir
        if ($trabalho->arquivo && \Storage::exists('public/' . $trabalho->arquivo)) {
            \Storage::delete('public/' . $trabalho->arquivo);
        }

        // Salvar o novo arquivo
        $file = $request->file('arquivo');
        $path = $file->store('trabalhos', 'public'); // Salvar na pasta 'storage/app/public/trabalhos'
        $validatedData['arquivo'] = $path; // Atualizar o caminho do arquivo no banco de dados
    }

    // Atualizar o registro com os dados validados
    $trabalho->update($validatedData);

    // Redirecionar com uma mensagem de sucesso
    return redirect()->route('trabalhos.index')->with('success', 'Trabalho atualizado com sucesso!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trabalho $trabalho)
    {
        DB::beginTransaction();
        try {
            Storage::disk('public')->delete($trabalho->arquivo);
            $trabalho->delete();
            DB::commit();

            return redirect()->route('trabalhos.index')->with('success', 'Trabalho deletado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Erro ao deletar trabalho: ' . $e->getMessage());
        }
    }

    /**
     * Download the specified file.
     */
    public function download($id)
    {
        $trabalho = Trabalho::findOrFail($id);
        if (!Storage::disk('public')->exists($trabalho->arquivo)) {
            abort(404);
        }

        return response()->download(storage_path('app/public/' . $trabalho->arquivo));
    }

    /**
     * Visualize the specified file.
     */
    public function visualize($id)
    {
        $trabalho = Trabalho::findOrFail($id);

        if (!Storage::disk('public')->exists($trabalho->arquivo)) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $trabalho->arquivo);
        return response()->file($filePath);
    }

    /**
     * Search for resources.
     */
// public function search(Request $request)
// {
//     try {
//         $search = $request->input('search');
        
//         // Verifique se o campo 'search' não está vazio
//         if (!$search) {
//             return response()->json(['error' => 'Campo de pesquisa não pode estar vazio.'], 400);
//         }

//         // Realize a pesquisa nos campos desejados
//         $trabalhos = Trabalho::where('titulo', 'like', '%' . $search . '%')
//                              ->orWhere('autor', 'like', '%' . $search . '%')
//                              ->get();

//         // Retorne a view parcial com os resultados da pesquisa
//         return view('trabalhos.partials.table', compact('trabalhos'))->render();
//     } catch (\Exception $e) {
//         // Log do erro
//         \Log::error('Erro na pesquisa: ' . $e->getMessage());
//         return response()->json(['error' => 'Ocorreu um erro ao realizar a pesquisa.'], 500);
//     }
// }


    /**
     * Validate incoming data.
     */
    private function validateData(Request $request, $id = null)
    {
        return $request->validate([
            'tipo' => 'required|string|max:50',
            'autor' => 'required|string|max:255',
            'titulo' => 'required|string|max:1500',
            'orientador' => 'required|string|max:1500',
            'resumo' => 'required',
            'abstract' => 'required',
            'palavras_chave' => 'required|string|max:255',
            'idioma' => 'required|string|max:50',
            'pais' => 'required|string|max:100',
            'instituicao' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'uri' => 'required|string|unique:trabalhos,uri' . ($id ? ',' . $id : ''),
            'data_documento' => 'required|date',
            'descricao' => 'nullable',
            'arquivo' => 'nullable|file|mimes:pdf,doc,docx'
        ]);
    }

    /**
     * Handle file upload.
     */
    private function uploadFile($file)
    {
        return $file->store('arquivos', 'public');
    }
}
