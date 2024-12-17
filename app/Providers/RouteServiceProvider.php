<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
  /**
   * O namespace para os controladores.
   *
   * @var string
   */
  protected $namespace = 'App\Http\Controllers';

  /**
   * Registre os serviços de rota.
   *
   * @return void
   */
  public function boot()
  {
    $this->mapApiRoutes();

    // Chame outras configurações, se necessário
  }

  /**
   * Mapeie as rotas da API.
   *
   * @return void
   */
  protected function mapApiRoutes()
  {
    Route::prefix('api')
      ->middleware('api')
      ->namespace($this->namespace)
      ->group(base_path('routes/api.php'));
  }

  /**
   * Registre os serviços.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
