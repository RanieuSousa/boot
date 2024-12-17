<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertaEmail extends Mailable
{
  use Queueable, SerializesModels;

  public $titulo;
  public $mensagem;

  /**
   * Create a new message instance.
   */
  public function __construct($titulo, $mensagem)
  {
    $this->titulo = $titulo;
    $this->mensagem = $mensagem;
  }

  /**
   * Build the message.
   */
  public function build()
  {

    return $this->subject($this->titulo) // Define o título como o assunto do e-mail
    ->view('emails.alerta')
      ->with([
        'mensagem' => $this->mensagem,
      ]);
  }
}
