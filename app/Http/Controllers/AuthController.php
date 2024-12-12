<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $client = new Client();
        
        try {
            $response = $client->post('http://Apivitrine/public/api/login', [
                'form_params' => [
                    'email' => $request->input('email'),
                    'password' => $request->input('password'),
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            // Armazene o token ou faça qualquer outra ação necessária após o login bem-sucedido

            return redirect()->intended('/dashboard'); // Redireciona para o painel após o login
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Credenciais inválidas']); // Redireciona de volta com mensagem de erro
        }
    }
}
