<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
  /**
   * Cria registros de empresas a partir dos clientes.
   *
   * Este método verifica se existe uma empresa com o nome de cada cliente. Se não existir, cria uma nova
   * empresa no banco de dados.
   *
   * @return void
   */
  public function empresa()
  {
    // Recupera todos os clientes do banco de dados
    $clientes = Cliente::all();

    // Itera sobre todos os clientes
    foreach ($clientes as $cliente) {
      // Verifica se já existe uma empresa com o mesmo nome do cliente
      $empresasExistente = Empresa::where('nome', $cliente->empresa)->first();

      if (!$empresasExistente) {
        // Se a empresa não existir, cria um novo registro de empresa
        $empresas = new Empresa();
        $empresas->nome = $cliente->empresa; // Atribui o nome da empresa do cliente
        $empresas->save(); // Salva a nova empresa no banco de dados
      }
    }
  }
}
