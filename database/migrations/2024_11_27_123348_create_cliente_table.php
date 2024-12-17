<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cliente', function (Blueprint $table) {
          $table->id();
          $table->integer( 'codigo' );
          $table->string( 'nome' );
          $table->integer( 'venda_id' )->nullable();
          $table->string( 'email' );
          $table->string( 'telefone' );
          $table->decimal( 'valor' );
          $table->string( 'vendedor' );
          $table->string( 'empresa' );
          $table->date( 'data_vencimento' );
          $table->date( 'data_previsao' );
          $table->integer( 'ciclo_id' );
          $table->integer( 'status' );
          $table->string( 'tipo' );
          $table->string( 'prazo' );
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};
