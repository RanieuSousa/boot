<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ciclus;
use App\Models\Mensagem;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Classe CiclusController
 *
 * Controlador responsável pela gestão dos ciclos (Ciclus) na administração da aplicação.
 *
 * Métodos:
 * - index: Exibe a lista de ciclos cadastrados.
 * - create: Exibe o formulário para cadastrar um novo ciclo.
 * - store: Armazena um novo ciclo no banco de dados.
 * - edit: Exibe o formulário para editar um ciclo existente.
 * - destroy: Deleta um ciclo do banco de dados.
 */
class CiclusController extends Controller
{
  /**
   * Exibe a lista de ciclos cadastrados.
   *
   * Método responsável por recuperar todos os ciclos do banco de dados e exibir na página de listagem.
   * Realiza um join com a tabela 'mensagem' para exibir o título da mensagem associada ao ciclo.
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    // Recupera todos os ciclos com a mensagem associada
    $ciclus = Ciclus::join('mensagem', 'mensagem.id', '=', 'ciclus.memsagem_id')
      ->select('ciclus.id', 'ciclus.dias', 'mensagem.titulo')
      ->get();

    // Retorna a view com a lista de ciclos
    return view('admin.ciclus.index', compact('ciclus'));
  }

  /**
   * Exibe o formulário para cadastrar um novo ciclo.
   *
   * Este método exibe o formulário de cadastro de ciclo, trazendo as mensagens do tipo 2
   * para serem associadas ao ciclo.
   *
   * @return \Illuminate\View\View
   */
  public function create()
  {
    // Recupera as mensagens do tipo 2 para associação com o ciclo
    $mensagem = Mensagem::where('tipo', 2)->get();

    // Retorna a view de criação de ciclo
    return view('admin.ciclus.create', compact('mensagem'));
  }

  /**
   * Armazena um novo ciclo no banco de dados.
   *
   * Este método valida os dados recebidos da requisição, cria um novo ciclo com as informações
   * validadas e redireciona para a página de listagem com uma mensagem de sucesso.
   * Caso ocorra um erro, uma mensagem de erro é retornada.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    try {
      // Validação dos dados de entrada
      $validatedData = $request->validate([
        'dias' => 'required|integer|min:1', // Valida que 'dias' é um número inteiro maior ou igual a 1
        'memsagem' => 'required|integer|min:1', // Valida que o ID da mensagem é um número inteiro válido
      ]);

      // Criação do novo registro 'Ciclus'
      $ciclus = new Ciclus();
      $ciclus->dias = $validatedData['dias'];
      $ciclus->memsagem_id = $validatedData['memsagem'];
      $ciclus->save(); // Salva o novo ciclo no banco de dados

      // Redireciona para a lista de ciclos com uma mensagem de sucesso
      return redirect()->route('ciclus')->with('success', 'Ciclus cadastrado com sucesso!');
    } catch (ValidationException $e) {
      // Tratamento de erros de validação, retorna com os erros
      return redirect()->back()
        ->withErrors($e->errors())
        ->withInput();
    } catch (\Exception $e) {
      // Tratamento de outros erros, retorna com uma mensagem de erro genérica
      return redirect()->back()
        ->with('error', 'Ocorreu um erro ao cadastrar o ciclo.')
        ->withInput();
    }
  }

  /**
   * Exibe o formulário para editar um ciclo existente.
   *
   * Este método busca o ciclo pelo ID, recupera as mensagens do tipo 2 e exibe a página de edição
   * com os dados do ciclo e as mensagens disponíveis.
   *
   * @param  int  $id
   * @return \Illuminate\View\View
   */
  public function edit($id)
  {
    // Busca o ciclo pelo ID
    $ciclus = Ciclus::find($id);

    // Recupera as mensagens do tipo 2 para associação
    $mensagems = Mensagem::where('tipo', 2)->get();

    // Recupera a mensagem associada ao ciclo
    $mensagem = Mensagem::find($ciclus->memsagem_id);

    // Retorna a view de edição com os dados do ciclo e as mensagens disponíveis
    return view('admin.ciclus.edit', compact('mensagem', 'ciclus', 'mensagems'));
  }

  /**
   * Deleta um ciclo do banco de dados.
   *
   * Este método encontra o ciclo pelo ID e o deleta do banco de dados. Após a exclusão, o usuário é
   * redirecionado de volta à lista de ciclos com uma mensagem de sucesso ou erro.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy($id)
  {
    try {
      // Encontra o ciclo pelo ID
      $ciclus = Ciclus::findOrFail($id);

      // Deleta o ciclo encontrado
      $ciclus->delete();

      // Retorna à lista de ciclos com uma mensagem de sucesso
      return redirect()->route('ciclus')->with('success', 'Ciclus deletado com sucesso!');
    } catch (\Exception $e) {
      // Retorna à lista de ciclos com uma mensagem de erro
      return redirect()->route('ciclus')->with('error', 'Ocorreu um erro ao deletar o ciclus.');
    }
  }
}
