<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mensagem;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MensagemController extends Controller
{
  /**
   * Exibe a lista de mensagens.
   *
   * Recupera todas as mensagens e retorna para a visão `admin.mensagem.index`.
   *
   * @return \Illuminate\View\View
   */
  public function index(){
    $mensagens = Mensagem::all();  // Recupera todas as mensagens do banco de dados
    return view('admin.mensagem.index', compact('mensagens'));  // Exibe a lista de mensagens
  }

  /**
   * Exibe o formulário para criar uma nova mensagem.
   *
   * @return \Illuminate\View\View
   */
  public function create(){
    return view('admin.mensagem.create');  // Retorna a visão para criar uma nova mensagem
  }

  /**
   * Salva uma nova mensagem no banco de dados.
   *
   * Valida os dados recebidos e verifica se já existe uma mensagem do tipo "vendas".
   * Caso já exista, exibe uma mensagem de erro. Caso contrário, salva a nova mensagem.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
   */
  public function store(Request $request)
  {
    try {
      // Valida os dados recebidos no formulário
      $validatedData = $request->validate([
        'titulo' => 'required|string|max:255',
        'tipo' => 'required|string|max:255',
        'mensagem' => 'required|string',
      ]);

      // Verifica se já existe uma mensagem do tipo "vendas"
      if ($validatedData['tipo'] == '1' && Mensagem::where('tipo', '1')->exists()) {
        // Retorna um erro caso já exista uma mensagem com tipo "vendas"
        return redirect()->back()->with('error', 'Já existe uma mensagem com o tipo "Vendas". Não é possível criar outra.');
      }

      // Cria uma nova instância de Mensagem com os dados validados
      $mensagem = new Mensagem();
      $mensagem->titulo = $validatedData['titulo'];
      $mensagem->tipo = $validatedData['tipo'];
      $mensagem->mensagem = $validatedData['mensagem'];
      $mensagem->save();  // Salva a mensagem no banco de dados

      // Retorna uma resposta de sucesso
      return redirect()->route('mensagem')->with('success', 'Mensagem enviada com sucesso!');
    } catch (ValidationException $e) {
      // Retorna os erros de validação em formato JSON
      return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
      // Retorna um erro genérico caso haja algum problema ao salvar
      return response()->json(['error' => 'Ocorreu um erro ao salvar a mensagem.'], 500);
    }
  }

  /**
   * Exibe o formulário para editar uma mensagem existente.
   *
   * @param int $id O ID da mensagem a ser editada.
   * @return \Illuminate\View\View
   */
  public function edit($id){
    $mensagens = Mensagem::find($id);  // Encontra a mensagem pelo ID
    return view('admin.mensagem.edit', compact('mensagens'));  // Exibe o formulário de edição
  }

  /**
   * Atualiza uma mensagem existente.
   *
   * Valida os dados recebidos, verifica se não existe outra mensagem do tipo "vendas" e atualiza a mensagem.
   * Retorna uma resposta de sucesso ou erro conforme o caso.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id O ID da mensagem a ser atualizada.
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
   */
  public function update(Request $request, $id)
  {
    try {
      // Valida os dados recebidos para a atualização
      $validatedData = $request->validate([
        'titulo' => 'required|string|max:255',
        'tipo' => 'required|string|max:255',
        'mensagem' => 'required|string',
      ]);

      // Verifica se já existe uma mensagem do tipo "vendas" e se não é a mesma mensagem sendo editada
      if ($validatedData['tipo'] == '1' && Mensagem::where('tipo', '1')->where('id', '!=', $id)->exists()) {
        // Retorna um erro caso já exista uma mensagem com tipo "vendas"
        return redirect()->back()->with('error', 'Já existe uma mensagem com o tipo "Vendas". Não é possível criar outra.');
      }

      // Encontra a mensagem pelo ID
      $mensagem = Mensagem::findOrFail($id);

      // Atualiza os dados da mensagem
      $mensagem->titulo = $validatedData['titulo'];
      $mensagem->tipo = $validatedData['tipo'];
      $mensagem->mensagem = $validatedData['mensagem'];
      $mensagem->save();  // Salva as alterações no banco de dados

      // Retorna uma resposta de sucesso
      return redirect()->route('mensagem')->with('success', 'Mensagem atualizada com sucesso!');
    } catch (ValidationException $e) {
      // Retorna os erros de validação em formato JSON
      return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
      // Retorna um erro genérico caso haja algum problema ao atualizar
      return response()->json(['error' => 'Ocorreu um erro ao atualizar a mensagem.'], 500);
    }
  }

  /**
   * Deleta uma mensagem do banco de dados.
   *
   * Encontra a mensagem pelo ID e a deleta. Retorna uma resposta de sucesso ou erro conforme o caso.
   *
   * @param int $id O ID da mensagem a ser deletada.
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy($id)
  {
    try {
      // Encontra a mensagem pelo ID
      $mensagem = Mensagem::findOrFail($id);

      // Deleta a mensagem
      $mensagem->delete();

      // Redireciona com sucesso
      return redirect()->route('mensagem')->with('success', 'Mensagem deletada com sucesso!');
    } catch (\Exception $e) {
      // Caso ocorra algum erro, exibe mensagem de erro
      return redirect()->route('mensagem')->with('error', 'Ocorreu um erro ao tentar deletar a mensagem.');
    }
  }
}
