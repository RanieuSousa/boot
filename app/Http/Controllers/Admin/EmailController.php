<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class EmailController extends Controller
{
  /**
   * Exibe as configurações de e-mail do sistema.
   *
   * Recupera as configurações de e-mail do arquivo .env e as exibe na view.
   *
   * @return \Illuminate\View\View
   */
  public function showMailConfig()
  {
    // Recupera as configurações de e-mail armazenadas no arquivo .env
    $mailConfig = [
      'MAIL_MAILER' => env('MAIL_MAILER'),
      'MAIL_HOST' => env('MAIL_HOST'),
      'MAIL_PORT' => env('MAIL_PORT'),
      'MAIL_USERNAME' => env('MAIL_USERNAME'),
      'MAIL_PASSWORD' => env('MAIL_PASSWORD'), // Evite exibir isso em produção!
      'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
      'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
      'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),
    ];

    // Retorna a view com as configurações de e-mail
    return view('admin.email.email', compact('mailConfig'));
  }

  /**
   * Atualiza as configurações de e-mail no arquivo .env.
   *
   * Recebe os dados do formulário de configuração de e-mail, atualiza o arquivo .env com os novos valores e
   * retorna à página de configurações com uma mensagem de sucesso.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function storeMailConfig(Request $request)
  {
    // Recupera todos os dados do formulário de configuração de e-mail
    $data = $request->all();

    // Caminho do arquivo .env
    $envPath = base_path('.env');

    // Lê o conteúdo do arquivo .env
    $envContent = File::get($envPath);

    // Atualiza as variáveis de ambiente no arquivo .env
    foreach ($data as $key => $value) {
      // Substitui o valor da variável no arquivo .env
      $envContent = preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $envContent);
    }

    // Escreve o conteúdo atualizado de volta no arquivo .env
    File::put($envPath, $envContent);

    // Redireciona de volta para a página de configurações com uma mensagem de sucesso
    return redirect()->route('showMailConfig')->with('success', 'Configurações de e-mail atualizadas com sucesso!');
  }
}
