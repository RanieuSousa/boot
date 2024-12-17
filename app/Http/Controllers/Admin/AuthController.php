<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * Classe AuthController
 *
 * Controlador responsável por gerenciar a autenticação do usuário no painel administrativo.
 *
 * Métodos:
 * - index: Exibe a página de login.
 * - login: Realiza a autenticação do usuário.
 * - logout: Desloga o usuário e redireciona para a página de login.
 */
class AuthController extends Controller
{
  /**
   * Exibe a página de login.
   *
   * Método responsável por retornar a view de login quando a rota de login é acessada.
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    // Retorna a view de login
    return view('admin.login.login');
  }

  /**
   * Realiza o login do usuário.
   *
   * Valida as credenciais de login fornecidas pelo usuário e tenta autenticar.
   * Se a autenticação for bem-sucedida, o usuário é redirecionado para o dashboard.
   * Caso contrário, uma mensagem de erro é retornada.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function login(Request $request)
  {
    // Validação dos dados de entrada
    $request->validate([
      'email-username' => 'required|email', // Valida se o e-mail/nome de usuário é fornecido e é um e-mail válido
      'password' => 'required|min:6', // Valida se a senha é fornecida e tem pelo menos 6 caracteres
    ]);

    // Tentativa de autenticação do usuário
    if (Auth::attempt([
      'email' => $request->input('email-username'), // Obtém o e-mail ou nome de usuário do campo de entrada
      'password' => $request->input('password')     // Obtém a senha do campo de entrada
    ])) {
      // Caso a autenticação seja bem-sucedida, o usuário é redirecionado para o dashboard
      return redirect()->route('clientespotencial'); // Redireciona para a rota 'clientespotencial' (dashboard)
    }

    // Caso a autenticação falhe, redireciona o usuário de volta com um erro
    return redirect()->back()->withErrors([
      'email' => 'As credenciais informadas estão incorretas.' // Mensagem de erro para credenciais incorretas
    ]);
  }

  /**
   * Realiza o logout do usuário.
   *
   * Este método desloga o usuário da aplicação e o redireciona para a página de login com uma mensagem de sucesso.
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function logout()
  {
    // Realiza o logout do usuário
    Auth::logout(); // Método responsável por deslogar o usuário autenticado

    // Redireciona o usuário para a página de login com uma mensagem de sucesso
    return redirect()->route('login')->with('success', 'Você saiu com sucesso!'); // Mensagem indicando que o logout foi bem-sucedido
  }
}
