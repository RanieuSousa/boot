<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertaEmail;
use App\Models\Mensagem;
use Carbon\Carbon;
use App\Models\Ciclus;
use Illuminate\Http\Request;
;


class EnviarMensagemController extends Controller
{
  /**
   * Envia e-mails de alerta para os usuários, informando sobre clientes potenciais.
   *
   * Este método recupera os clientes potenciais, verifica os clientes de cobrança, e para cada cliente
   * potencial que não esteja na lista de cobrança, envia um e-mail aos usuários que correspondem ao grupo e empresa
   * do cliente, com uma mensagem personalizada.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function email()
  {
    // Recupera os clientes potenciais que não compraram nos últimos 30 dias e cujo valor é maior que 50000
    $clientesPotenciais = DB::table('cliente_potencial')
      ->where('ultima_compra', '<', DB::raw('NOW() - INTERVAL 30 DAY'))
      ->where('status', 1) // Considera apenas os clientes com status 1
      ->where('valor', '>', 50000)
      ->get();

    // Recupera todos os clientes de cobrança
    $clientecobranca = Cliente::all();

    // Loop através dos clientes potenciais
    foreach ($clientesPotenciais as $cliente) {
      // Verifica se o cliente_id do cliente potencial é diferente do código de qualquer cliente de cobrança
      foreach ($clientecobranca as $cobranca) {
        if ($cliente->cliente_id != $cobranca->codigo) {
          // Recupera os usuários que fazem parte de grupos e empresas
          $usuarios = User::select(
            'users.id as user_id',
            'users.name as user_name',
            'users.email as user_email',
            'grupos.nome as grupo_nome',
            'empresa.nome as empresa_nome'
          )
            ->leftJoin('grupo_users', 'grupo_users.users_id', '=', 'users.id')
            ->leftJoin('grupos', 'grupos.id', '=', 'grupo_users.grupo_id')
            ->leftJoin('empresa_users', 'empresa_users.users_id', '=', 'users.id')
            ->leftJoin('empresa', 'empresa.id', '=', 'empresa_users.empresa_id')
            ->where('type', 1) // Filtra para usuários do tipo 1
            ->get();

          // Recupera a mensagem de alerta que será enviada por e-mail
          $mensagem = Mensagem::where('tipo', 1)->first();

          // Se a mensagem não for encontrada, retorna erro
          if (!$mensagem) {
            return response()->json(['error' => 'Mensagem não encontrada'], 404);
          }

          // Loop através dos usuários
          foreach ($usuarios as $usuario) {
            // Verifica se o grupo e a empresa correspondem ao cliente
            if ($usuario->grupo_nome == $cliente->vendedor && $usuario->empresa_nome == $cliente->empresa) {
              // Substitui as chaves na mensagem com as informações reais do cliente e usuário
              $mensagemSubstituida = $this->substituirChavesNaMensagem($mensagem->mensagem, $cliente, $usuario);

              // Envia o e-mail com a mensagem substituída
              Mail::to($usuario->user_email)->send(new AlertaEmail($mensagem->titulo, $mensagemSubstituida));

              // Atualiza o status do cliente para 2 após o envio do e-mail
              DB::table('cliente_potencial')
                ->where('id', $cliente->id)
                ->update(['status' => 2]);
            }
          }

          // Se a condição foi atendida, interrompe o loop de clientes de cobrança
          break;
        }
      }
    }

    // Retorna uma resposta JSON indicando que os e-mails foram enviados com sucesso
    return response()->json(['message' => 'E-mails enviados com sucesso!']);
  }

  public function sendMessage(Request $request)
  {
    $clientes = Cliente::join('ciclus', 'ciclus.id', '=', 'cliente.ciclo_id')
      ->select('cliente.*', 'ciclus.dias')
      ->where('cliente.status', '!=', 3)
      ->get();

    $response = [];

    foreach ($clientes as $cliente) {
      $dataVencimento = Carbon::parse($cliente->data_vencimento);
      $diasRestantes = $dataVencimento->diffInDays(now(), false);
      $dataPrevisao = Carbon::parse($cliente->data_previsao)->addDays(2);

      if ($diasRestantes > $cliente->dias && $dataPrevisao < now()) {
        $cicloAtual = Ciclus::find($cliente->ciclo_id);
        $clienteupdate = Cliente::find($cliente->id);
        $clienteupdate->status = 2;

        $proximoCiclo = Ciclus::where('id', '>', $cicloAtual->id)->orderBy('id', 'asc')->first();
        $mensagem = Mensagem::where('tipo', 2)->where('id', $cicloAtual->memsagem_id)->first();
        $telefone = $cliente->telefone;

        try {
          $mensagemPersonalizada = $this->substituirChavesNaMensagem(
            $mensagem ? $mensagem->mensagem : 'Mensagem não encontrada',
            $cliente,
            $request->user()
          );

          $response[] = [
            'nome' => $cliente->nome,
            'telefone' => $telefone,
            'mensagem' => $mensagemPersonalizada,
          ];
        } catch (\Exception $e) {
          $response[] = [
            'nome' => $cliente->nome,
            'erro' => $e->getMessage(),
          ];
          continue;
        }

        if ($proximoCiclo) {
          $clienteupdate->ciclo_id = $proximoCiclo->id;
          $clienteupdate->data_previsao = now()->addDays($proximoCiclo->dias - 2)->format('Y-m-d');
        } else {
          $clienteupdate->status = 3;
        }

        $clienteupdate->update();
      } else {
        $response[] = [
          'nome' => $cliente->nome,
          'status' => 'Não enviar mensagem',
          'motivo' => 'O cliente não atende aos critérios de atraso ou ciclo'
        ];
      }
    }

    return response()->json($response);
  }

  /**
   * Substitui as chaves na mensagem com os dados do cliente e do usuário.
   *
   * @param string $mensagem A mensagem com as chaves a serem substituídas
   * @param object $cliente O cliente que contém as informações a serem inseridas
   * @param object $usuario O usuário que contém as informações a serem inseridas
   * @return string A mensagem com as chaves substituídas
   */
  private function substituirChavesNaMensagem($mensagem, $cliente, $usuario)
  {
    // Substitui as chaves com os valores reais
    $mensagem = str_replace('[codigo]', $cliente->cliente_id, $mensagem);
    $mensagem = str_replace('[nome]', $cliente->nome, $mensagem);
    $mensagem = str_replace('[vendedor]', $usuario->user_name, $mensagem);
    $mensagem = str_replace('[ultima_compra]', Carbon::parse($cliente->ultima_compra)->format('d/m/Y'), $mensagem);

    // Calcula os dias desde a última compra usando Carbon
    $diasSemCompra = intval(Carbon::parse($cliente->ultima_compra)->diffInDays(Carbon::now()));
    $mensagem = str_replace('[dias_sem_compara]', $diasSemCompra, $mensagem);

    return $mensagem;
  }
}
