<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
  /**
   * Cria grupos baseados nos vendedores dos clientes.
   *
   * Este método recupera todos os clientes e verifica se já existe um grupo com o nome do vendedor.
   * Caso não exista, ele cria um novo grupo com o nome do vendedor do cliente.
   *
   * @return void
   */
  public function grupos()
  {
    // Recupera todos os clientes da base de dados
    $clientes = Cliente::all();

    // Loop através dos clientes
    foreach ($clientes as $cliente) {
      // Verifica se já existe um grupo com o nome do vendedor
      $grupoExistente = Grupo::where('nome', $cliente->vendedor)->first();

      // Se não existir, cria um novo grupo
      if (!$grupoExistente) {
        $grupo = new Grupo();
        $grupo->nome = $cliente->vendedor; // Define o nome do grupo como o vendedor do cliente
        $grupo->save(); // Salva o novo grupo no banco de dados
      }
    }
  }
}
