<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
   * Define os comandos Artisan do aplicativo.
   *
   * @var array
   */
  protected $commands = [
    // Adicione aqui seus comandos, se necessário
  ];

  /**
   * Defina as tarefas agendadas.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    // Agende seu comando para executar à meia-noite
    $schedule->command('emails:enviar')->dailyAt('14:21');
  }

  /**
   * Registre os comandos do console do aplicativo.
   *
   * @return void
   */
  protected function commands()
  {
    $this->load(__DIR__.'/Commands');

    require base_path('routes/console.php');
  }

}
