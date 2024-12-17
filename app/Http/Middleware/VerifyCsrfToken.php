<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class VerifyCsrfToken extends Middleware
{
  /**
   * O array de rotas que não exigem verificação de CSRF.
   *
   * @var array
   */
  protected $except = [
    'receive-qr', // Adicione a rota para não ser verificada
  ];
}
