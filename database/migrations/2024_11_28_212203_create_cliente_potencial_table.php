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
    Schema::create('cliente_potencial', function (Blueprint $table) {
      $table->id();
      $table->integer('cliente_id');
      $table->string('nome');
      $table->string('vendedor');
      $table->string('empresa');
      $table->dateTime('ultima_compra')->nullable(); // Corrigido de 'datetimes' para 'dateTime'
      $table->decimal('valor', 15, 2);
      $table->integer('status')->default(1);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cliente_potencial');
  }
};
