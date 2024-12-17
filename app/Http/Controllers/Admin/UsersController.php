<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Empresauses;
use App\Models\Grupo;
use App\Models\Grupouser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
  /**
   * Exibe a lista de usuários com seus grupos e empresas.
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    $users = User::select(
      'users.id as user_id',
      'users.photo',
      'users.type',
      'users.name as user_name',
      'users.email as user_email',
      DB::raw("GROUP_CONCAT(DISTINCT grupos.nome ORDER BY grupos.nome SEPARATOR ', ') as grupos"),
      DB::raw("GROUP_CONCAT(DISTINCT empresa.nome ORDER BY empresa.nome SEPARATOR ', ') as empresas")
    )
      ->leftJoin('grupo_users', 'grupo_users.users_id', '=', 'users.id')
      ->leftJoin('grupos', 'grupos.id', '=', 'grupo_users.grupo_id')
      ->leftJoin('empresa_users', 'empresa_users.users_id', '=', 'users.id')
      ->leftJoin('empresa', 'empresa.id', '=', 'empresa_users.empresa_id')
      ->groupBy('users.id', 'users.name', 'users.email')
      ->get();

    return view('admin.users.index', compact('users'));
  }

  /**
   * Exibe o formulário para criar um novo usuário.
   * Chama as funções que criam ou atualizam grupos e empresas, e recupera os dados necessários.
   *
   * @return \Illuminate\View\View
   */
  public function create()
  {
    // Chama o método grupos do GrupoController para garantir que os grupos sejam criados
    app('App\Http\Controllers\Admin\GrupoController')->grupos();

    // Chama o método empresa do EmpresaController para garantir que as empresas sejam criadas
    app('App\Http\Controllers\Admin\EmpresaController')->empresa();

    // Recupera todos os grupos e empresas para mostrar no formulário
    $grupos = Grupo::all();
    $empresas = Empresa::all();

    return view('admin.users.create', compact('grupos', 'empresas'));
  }

  /**
   * Armazena um novo usuário no banco de dados, associando-o a grupos e empresas.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    // Validação dos dados recebidos
    try {
      $validatedData = $request->validate([
        'nome' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email|max:255',
        'senha' => 'required|string|min:6',
        'empresas' => 'required|array',
        'type' => 'nullable|integer',
        'grupos' => 'required|array',
      ]);

      // Criação do usuário no banco de dados
      $user = new User();
      $user->name = $validatedData['nome'];
      $user->email = $validatedData['email'];
      $user->password = Hash::make($validatedData['senha']);
      $user->type = $validatedData['type'];
      $user->save();

      // Associação das empresas ao usuário
      foreach ($validatedData['empresas'] as $empresaId) {
        $empresaUser = new Empresauses();
        $empresaUser->empresa_id = $empresaId;
        $empresaUser->users_id = $user->id;
        $empresaUser->save();
      }

      // Associação dos grupos ao usuário
      foreach ($validatedData['grupos'] as $grupoId) {
        $grupoUser = new Grupouser();
        $grupoUser->grupo_id = $grupoId;
        $grupoUser->users_id = $user->id;
        $grupoUser->save();
      }

      // Redireciona para a página de usuários com sucesso
      return redirect()->route('users')->with('success', 'Usuário criado com sucesso!');
    } catch (\Illuminate\Validation\ValidationException $e) {
      // Retorna os erros de validação para o formulário de criação
      return redirect()->route('createusers')
        ->withErrors($e->errors())
        ->withInput();
    }
  }

  /**
   * Exibe o formulário para editar um usuário existente.
   *
   * @param int $id
   * @return \Illuminate\View\View
   */
  public function edit($id)
  {
    $user = User::find($id);
    $grupos = Grupo::all();
    $empresas = Empresa::all();
    $grupoUsers = GrupoUser::where('users_id', $id)->get();
    $empresaUsers = Empresauses::where('users_id', $id)->get();

    return view('admin.users.edit', compact('user', 'grupos', 'empresas', 'grupoUsers', 'empresaUsers'));
  }

  /**
   * Atualiza as informações de um usuário no banco de dados.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, $id)
  {
    // Validação dos dados recebidos
    try {
      $validatedData = $request->validate([
        'nome' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id . '|max:255',
        'senha' => 'nullable|string|min:6',
        'empresas' => 'required|array',
        'type' => 'nullable|integer',
        'grupos' => 'required|array',
      ]);

      // Atualiza as informações do usuário
      $user = User::findOrFail($id);
      $user->name = $validatedData['nome'];
      $user->email = $validatedData['email'];
      $user->type = $validatedData['type'];

      // Atualiza a senha apenas se for fornecida
      if (!empty($validatedData['senha'])) {
        $user->password = Hash::make($validatedData['senha']);
      }

      $user->save();

      // Remove as associações antigas de empresas e grupos
      Empresauses::where('users_id', $id)->delete();
      Grupouser::where('users_id', $id)->delete();

      // Reassocia as empresas e grupos ao usuário
      foreach ($validatedData['empresas'] as $empresaId) {
        $empresaUser = new Empresauses();
        $empresaUser->empresa_id = $empresaId;
        $empresaUser->users_id = $user->id;
        $empresaUser->save();
      }

      foreach ($validatedData['grupos'] as $grupoId) {
        $grupoUser = new Grupouser();
        $grupoUser->grupo_id = $grupoId;
        $grupoUser->users_id = $user->id;
        $grupoUser->save();
      }

      return redirect()->route('users')->with('success', 'Usuário atualizado com sucesso!');
    } catch (\Illuminate\Validation\ValidationException $e) {
      return redirect()->route('editusers', ['id' => $id])
        ->withErrors($e->errors())
        ->withInput();
    }
  }

  /**
   * Exibe o perfil do usuário autenticado.
   *
   * @return \Illuminate\View\View
   */
  public function perfil()
  {
    return view('admin.profile.perfil');
  }

  /**
   * Atualiza o perfil do usuário autenticado.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function updateprofile(Request $request)
  {
    // Encontra o usuário autenticado
    $user = User::find(Auth::id());

    // Atualiza os campos nome e email
    $user->name = $request->input('name');
    $user->email = $request->input('email');

    // Verifica se uma nova foto foi enviada e atualiza a foto do usuário
    if ($request->hasFile('photo')) {
      $folder = 'profile';

      // Remove a foto antiga se houver
      if ($user->photo && file_exists(public_path('storage/' . $user->photo))) {
        unlink(public_path('storage/' . $user->photo));
      }

      // Salva a nova foto
      $path = $request->file('photo')->store($folder, 'public');
      $user->photo = $path;
    }

    // Salva as alterações
    $user->update();

    return redirect()->route('perfil')->with('success', 'Perfil atualizado com sucesso!');
  }

  /**
   * Atualiza a senha do usuário autenticado.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function updatepassword(Request $request)
  {
    // Encontra o usuário autenticado
    $user = User::find(Auth::id());
    $user->password = Hash::make($request->input('senha'));
    $user->update();

    return redirect()->route('perfil')->with('success', 'Senha atualizada com sucesso!');
  }

  /**
   * Deleta um usuário e suas associações com empresas e grupos.
   *
   * @param int $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy($id)
  {
    // Remove as associações do usuário com empresas e grupos
    Empresauses::where('users_id', $id)->delete();
    Grupouser::where('users_id', $id)->delete();

    // Exclui o usuário
    User::find($id)->delete();

    return redirect()->route('users')->with('success', 'Usuário excluído com sucesso!');
  }
}
