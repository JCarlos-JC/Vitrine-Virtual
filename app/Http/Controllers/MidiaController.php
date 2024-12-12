<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Midia;
use App\Models\User;
use Illuminate\Support\Facades\Notification; // Adicione este import
use App\Notifications\ConteudoDisponibilizado; // Adicione este import
use App\Mail\ConteudoDisponibilizadoMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Conteudo;

class MidiaController extends Controller
{
    // Método para exibir todos os conteúdos de mídia
    public function index()
    {
        $conteudos = Midia::all();
            $midias = Midia::all();
        return view('midias.index', compact('conteudos','midias'));
    }

    // Método para exibir o formulário de criação de conteúdo de mídia
    public function create()
    {
        return view('midias.create');
    }

    // Método para armazenar um novo conteúdo de mídia
public function store(Request $request)
{
    // Validar os dados do formulário
    $request->validate([
        'titulo'=> 'required',
        'descricao'=> 'required',
        'arquivo' => 'nullable|mimes:jpeg,png,jpg,gif',
        'disponivel' => 'required|boolean',
    ]);


    if($request->has('arquivo')){

        $file = $request->file('arquivo');
        $extension = $file->getClientOriginalExtension();

        $filename = time(). '.'.$extension;
        $path = 'uploads/midias/';
        $file->move($path, $filename);


    }

    // Salvar o arquivo na pasta de armazenamento
    // $arquivoOriginalNome = $request->file('arquivo')->getClientOriginalName();
    // $path = $request->file('arquivo')->storeAs('midias', $arquivoOriginalNome);

    // Criar um novo objeto de mídia
    $midia = new Midia();
    $midia->titulo = $request->titulo;
    $midia->descricao = $request->descricao;
    $midia->arquivo = $path.$filename; // Armazenar o caminho completo do arquivo
    $midia->disponivel = $request->disponivel;
    $midia->save();

    // Redirecionar de volta com uma mensagem de sucesso
    return redirect()->route('midias.index')->with('success', 'Conteúdo adicionado com sucesso.');
}




    // Método para exibir detalhes de um conteúdo de mídia
    // public function show($id)
    // {
    //     $conteudo = Midia::findOrFail($id);
    //     return view('midias.show', compact('conteudo'));
    // }

    // Método para exibir o formulário de edição de conteúdo de mídia
   public function edit($id)
    {
        $conteudo = Midia::findOrFail($id);
        return view('midias.edit', compact('conteudo'));
    }

    // Método para atualizar um conteúdo de mídia existente
    public function update(Request $request, $id)
    {
        // Validação dos dados do formulário
        $request->validate([
        'titulo'=> 'required',
        'descricao'=> 'required',
        'arquivo' => 'nullable|mimes:jpeg,png,jpg,gif',
        'disponivel' => 'required|boolean',
        ]);

        // Atualização do registro no banco de dados
              $conteudo = Midia::findOrFail($id);
        $conteudo->update($request->all());

        // Verificação e atualização do arquivo de mídia, se necessário
        if($request->has('arquivo')){

        $file = $request->file('arquivo');
        $extension = $file->getClientOriginalExtension();

        $filename = time(). '.'.$extension;
        $path = 'uploads/midias/';
        $file->move($path, $filename);


    }

        $conteudo->save();

        // Redirecionamento para a página de visualização do conteúdo
        return redirect()->route('midias.index', $conteudo->id);
    }

    // Método para eliminar um conteúdo de mídia
    public function destroy($id)
    {
        // Busca e eliminação do registro no banco de dados
        $conteudo = Midia::findOrFail($id);
        $conteudo->delete();

        // Redirecionamento para a página de listagem de conteúdos
        return redirect()->route('midias.index');
    }

    // Método para disponibilizar ou indisponibilizar um conteúdo de mídia
 public function disponibilizar($id)
    {
        // Encontrar o conteúdo pelo ID
        $conteudo = Midia::findOrFail($id);
        // Alterar o status de disponibilidade
        $conteudo->disponivel = !$conteudo->disponivel;
        // Salvar as alterações
        $conteudo->save();

        if ($conteudo->disponivel) {
        // Enviar notificação para todos os usuários
        $users = User::all();
        Notification::send($users, new ConteudoDisponibilizado($conteudo));

            foreach ($users as $usuario) {
                Mail::to($usuario->email)->send(new ConteudoDisponibilizadoMail($conteudo));
            }
    }

        // Redirecionar de volta com uma mensagem de sucesso
        return redirect()->route('midias.index')->with('success', 'Status de disponibilidade atualizado com sucesso.');
    }
}
