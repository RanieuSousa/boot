<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\EnviarMensagemController;

class EnviarEmails extends Command
{
  /**
   * O nome e a assinatura do comando do console.
   *
   * @var string
   */
  protected $signature = 'emails:enviar';

  /**
   * A descrição do comando do console.
   *
   * @var string
   */
  protected $description = 'Envia e-mails para clientes potenciais';

  /**
   * Execute o comando do console.
   *
   * @return int
   */
  public function handle()
  {
    $controller = new EnviarMensagemController();
    $controller->email();

    $this->info('E-mails enviados com sucesso!');
    return 0;
  }
}
