<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\HistoricoCliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClienteController extends Controller
{
  public function index()
  {
    // Recupera o usuário logado com seus respectivos grupos
    $usuario = User::with('grupos') // Relacionamento "grupos" configurado no modelo User
    ->where('id', Auth::user()->id)
      ->first();

    // Verifica se o usuário existe
    if (!$usuario) {
      return redirect()->back()->with('error', 'Usuário não encontrado.');
    }

    // Calcula as datas de 15 e 29 dias atrás
    $dataInicio = Carbon::now()->subDays(29); // 29 dias atrás
    $dataFim = Carbon::now()->subDays(15); // 15 dias atrás
    $dataLimite = Carbon::now()->subDays(29); // Data limite para vencimento maior que 29 dias

    // Recupera os clientes com base nos grupos e empresas
    $clientes = Cliente::join('grupos', 'grupos.nome', '=', 'cliente.vendedor') // Associa grupos e vendedores
    ->join('empresa', 'empresa.nome', '=', 'cliente.empresa') // Associa empresas e clientes
    ->join('grupo_users', 'grupo_users.grupo_id', '=', 'grupos.id') // Relaciona grupos e usuários
    ->join('empresa_users', 'empresa_users.empresa_id', '=', 'empresa.id') // Relaciona empresas e usuários
    ->where('empresa_users.users_id', Auth::user()->id) // Filtra pela empresa do usuário
    ->where('grupo_users.users_id', Auth::user()->id) // Filtra pelos grupos do usuário
    ->whereBetween('cliente.data_vencimento', [$dataInicio, $dataFim]) // Filtra pela data de vencimento
    ->select('cliente.*') // Seleciona apenas os dados da tabela de clientes
    ->get();

    // Clientes com vencimento maior que 29 dias
    $clientes30 = Cliente::join('grupos', 'grupos.nome', '=', 'cliente.vendedor')
      ->join('empresa', 'empresa.nome', '=', 'cliente.empresa')
      ->join('grupo_users', 'grupo_users.grupo_id', '=', 'grupos.id')
      ->join('empresa_users', 'empresa_users.empresa_id', '=', 'empresa.id')
      ->where('empresa_users.users_id', Auth::user()->id)
      ->where('grupo_users.users_id', Auth::user()->id)
      ->where('cliente.data_vencimento', '<', $dataLimite)
      ->select('cliente.*')
      ->orderBy('cliente.data_vencimento', 'desc')
      ->get();

    // Retorna a view com os clientes filtrados
    return view('admin.cliente.index', compact('clientes', 'clientes30', 'usuario'));
  }



  public function edit($id)
  {
    // Encontra o cliente e o histórico
    $cliente = Cliente::find($id);
    $historico = HistoricoCliente::join('cliente', 'cliente.id', '=', 'historico_cliente.cliente_id')
      ->join('users', 'users.id', '=', 'historico_cliente.usuario')
      ->select('historico_cliente.*', 'users.name', 'users.photo', 'cliente.data_previsao')
      ->where('historico_cliente.cliente_id', $id)
      ->get();

    return view('admin.cliente.edit', compact('cliente', 'historico'));
  }

  public function update(Request $request, $id)
  {
    // Encontra o cliente e atualiza seus dados
    $cliente = Cliente::find($id);
    if (!$cliente) {
      return redirect()->route('clientes')->with('error', 'Cliente não encontrado.');
    }

    // Atualiza o cliente com os novos dados
    $cliente->data_previsao = $request->input('data');
    $cliente->status = 4;
    $cliente->save();

    // Criação do histórico de cliente
    $historico = new HistoricoCliente();
    $historico->cliente_id = $id;
    $historico->observacao = $request->input('observacao');
    $historico->usuario = Auth::user()->id;
    $historico->save();

    return redirect()->route('clientes')->with('success', 'Cliente atualizado com sucesso.');
  }
}
