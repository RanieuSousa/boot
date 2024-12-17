<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ciclus;
use App\Models\Cliente;
use App\Models\Mensagem;
use Carbon\Carbon;
use http\Client;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Cache;

class WhatsappController extends Controller
{
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

      // Verificar se o cliente atende aos critérios para o envio da mensagem
      if ($diasRestantes > $cliente->dias && $dataPrevisao < now()) {
        $cicloAtual = Ciclus::find($cliente->ciclo_id);
        $clienteupdate = Cliente::find($cliente->id);
        $clienteupdate->status = 2;

        // Encontrar o próximo ciclo
        $proximoCiclo = Ciclus::where('id', '>', $cicloAtual->id)->orderBy('id', 'asc')->first();
        // Obter a mensagem para o tipo 2
        $mensagem = Mensagem::where('tipo', 2)->where('id', $cicloAtual->memsagem_id)->first();
        $telefone = $cliente->telefone;

        // Formatar o número de telefone para o padrão internacional
        $telefoneFormatado = '55' . preg_replace('/\D/', '', $telefone); // Remove qualquer caractere não numérico e adiciona o código do país

        try {
          // Substituir as chaves na mensagem
          $mensagemPersonalizada = $this->substituirChavesNaMensagem(
            $mensagem ? $mensagem->mensagem : 'Mensagem não encontrada',
            $cliente,
            $request->user()
          );

          $response[] = [
            'nome' => $cliente->nome,
            'telefone' => $telefoneFormatado,  // Usar telefone formatado
            'mensagem' => $mensagemPersonalizada,
          ];
        } catch (\Exception $e) {
          $response[] = [
            'nome' => $cliente->nome,
            'erro' => $e->getMessage(),
          ];
          continue;
        }

        // Atualizar o ciclo do cliente, se houver próximo ciclo
        if ($proximoCiclo) {
          $clienteupdate->ciclo_id = $proximoCiclo->id;
          $clienteupdate->data_previsao = now()->addDays($proximoCiclo->dias - 2)->format('Y-m-d');
        } else {
          $clienteupdate->status = 3;  // Caso não haja próximo ciclo
        }

        $clienteupdate->update();
      } else {
        $response[] = [
          'nome' => $cliente->nome,
          'status' => 'Não enviar mensagem',
          'motivo' => 'O cliente não atende aos critérios de atraso ou ciclo',
        ];
      }
    }

    return response()->json($response);
  }
    private function substituirChavesNaMensagem($mensagem, $cliente)
  {
    $mensagem = str_replace('[nome]', $cliente->nome ?? 'N/A', $mensagem);

    // Converte o valor da dívida para o formato brasileiro
    $valorDebito = $cliente->valor ? 'R$ ' . number_format($cliente->valor, 2, ',', '.') : 'N/A';
    $mensagem = str_replace('[valor_debito]', $valorDebito, $mensagem);

    // Calcula dias de atraso
    $diasAtraso = $cliente->data_vencimento
      ? intval(Carbon::parse($cliente->data_vencimento)->diffInDays(now()))
      : 0;
    $mensagem = str_replace('[dias_atraso]', $diasAtraso, $mensagem);
    $mensagem = str_replace('[dias_atraso]', $diasAtraso, $mensagem);

    return $mensagem;
  }

  public function storeQRCode(Request $request)
  {
    $qrBase64 = $request->input('qr');
    if ($qrBase64) {
      Cache::put('whatsapp_qr', $qrBase64, now()->addMinutes(10)); // Salva o QR Code no cache
      return response()->json(['message' => 'QR Code armazenado com sucesso.']);
    }

    return response()->json(['message' => 'QR Code inválido.'], 400);
  }

  // Exibe o QR Code na view
  public function showQRCode()
  {
    $qrCode = Cache::get('whatsapp_qr', null); // Recupera o QR Code do cache
    if ($qrCode) {
      // Adiciona o prefixo base64 correto para exibição
      $qrCode = 'data:image/png;base64,' . $qrCode;
    }

    return view('admin.whatsapp.teste', compact('qrCode'));
  }



}
