<?php

namespace App\Http\Controllers\Admin;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Http\Controllers\Controller;

class PythonController extends Controller
{
  public function executePythonScripts()
  {
    // Caminhos para os scripts Python
    $clienteScript = storage_path('app/public/python/Cliente.py');
    $clientePotencialScript = storage_path('app/public/python/ClientePotencial.py');

    try {
      // Executa o Cliente.py
      $process1 = new Process([
        'C:\\Users\\Raniel\\AppData\\Local\\Programs\\Python\\Python312\\python.exe',
        storage_path('app/public/python/Cliente.py')
      ]);
      $process1->run();
      $process1->run();

      if (!$process1->isSuccessful()) {
        throw new ProcessFailedException($process1);
      }

      // Executa o ClientePotencial.py
      $process2 = $process1 = new Process(['C:\\Users\\Raniel\\AppData\\Local\\Programs\\Python\\Python312\\python.exe', $clientePotencialScript]);

      $process2->run();

      if (!$process2->isSuccessful()) {
        throw new ProcessFailedException($process2);
      }

      return response()->json([
        'message' => 'Scripts executados com sucesso!',
      ], 200);

    } catch (\Exception $e) {

      dd($e->getMessage());
      // Retorna a mensagem de erro
      return response()->json([
        'message' => 'Erro ao executar os scripts.',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
