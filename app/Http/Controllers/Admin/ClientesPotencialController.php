<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ClientePotencial;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientesPotencialController extends Controller
{
  /**
   * Exibe a lista de clientes potenciais filtrados de acordo com o vendedor e o valor.
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    // Recupera o usuário logado com seus respectivos grupos
    $usuario = User::with('grupos') // Assume que você tenha um relacionamento "grupos" no modelo User
    ->where('users.id', Auth::user()->id)
      ->first();

    // Recupera os nomes das empresas associadas ao usuário logado
    $empresas = User::where('users.id', Auth::user()->id)
      ->join('empresa_users', 'empresa_users.users_id', '=', 'users.id')
      ->join('empresa', 'empresa.id', '=', 'empresa_users.empresa_id')
      ->pluck('empresa.nome'); // Extrai apenas os nomes das empresas em um array

    // Verifica se o usuário existe
    if (!$usuario) {
      return redirect()->back()->with('error', 'Usuário não encontrado.');
    }

    // Filtra os clientes potenciais
    $clientes = DB::table('cliente_potencial')
      ->select(
        'cliente_potencial.id',
        'cliente_potencial.cliente_id',
        'cliente_potencial.nome',
        'cliente_potencial.empresa',
        'cliente_potencial.ultima_compra',
        'cliente_potencial.vendedor',
        'cliente_potencial.valor',
        'cliente_potencial.status',
        DB::raw('DATEDIFF(CURDATE(), cliente_potencial.ultima_compra) AS dias_sem_comprar')
      )
      ->where('cliente_potencial.valor', '>', 50000) // Filtra clientes com valor maior que 50.000
      ->whereIn('cliente_potencial.vendedor', $usuario->grupos->pluck('nome')) // Filtra pelo vendedor do grupo
      ->whereIn('cliente_potencial.empresa', $empresas) // Filtra pela empresa associada ao usuário
      ->whereNotIn('cliente_potencial.cliente_id', Cliente::pluck('codigo')) // Exclui clientes já cadastrados
      ->paginate(50); // Paginação de 50 itens por vez

    // Recupera todos os clientes para consulta
    $clientecobranca = Cliente::all();

    // Retorna a view com os dados dos clientes potenciais filtrados
    return view('admin.ClientesPotencial.index', compact('clientes', 'usuario'));
  }



  /**
   * Exibe a página de edição de um cliente potencial.
   *
   * @param int $id ID do cliente potencial a ser editado
   * @return void
   */
  public function edit($id)
  {
    // Recupera o cliente potencial pelo ID
    $cliente = ClientePotencial::find($id);

    // O código para edição ainda precisa ser implementado
  }

  /**
   * Desativa um cliente potencial, alterando seu status para "desativado".
   *
   * @param int $id ID do cliente potencial a ser desativado
   * @return \Illuminate\Http\RedirectResponse
   */
  public function desativar($id)
  {
    // Recupera o cliente potencial pelo ID
    $cliente = ClientePotencial::find($id);

    // Altera o status do cliente para 3 (desativado)
    $cliente->status = 3;
    $cliente->update(); // Atualiza o cliente no banco de dados

    // Redireciona para a página de clientes potenciais com uma mensagem de sucesso
    return redirect()->route('clientespotencial')->with('success', 'Cliente desativado com sucesso!');
  }

  /**
   * Ativa um cliente potencial, alterando seu status para "ativo".
   *
   * @param int $id ID do cliente potencial a ser ativado
   * @return \Illuminate\Http\RedirectResponse
   */
  public function ativar($id)
  {
    // Recupera o cliente potencial pelo ID
    $cliente = ClientePotencial::find($id);

    // Altera o status do cliente para 1 (ativo)
    $cliente->status = 1;
    $cliente->update(); // Atualiza o cliente no banco de dados

    // Redireciona para a página de clientes potenciais com uma mensagem de sucesso
    return redirect()->route('clientespotencial')->with('success', 'Cliente ativado com sucesso!');
  }
}
