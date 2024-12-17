<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ClientesPotencialController;
use App\Http\Controllers\Admin\MensagemController;
use App\Http\Controllers\Admin\CiclusController;
use App\Http\Controllers\Admin\ClienteController;

// Página principal
Route::get('/', [AuthController::class, 'index'])->name('login'); // Página de login
Route::post('login', [AuthController::class, 'login'])->name('admin.auth.login'); // Login do usuário
Route::post('logout', [AuthController::class, 'logout'])->name('logout'); // Logout do usuário

// Gerenciamento de usuários
Route::get('users', [UsersController::class, 'index'])->name('users')->middleware('auth'); // Lista de usuários
Route::get('create/users', [UsersController::class, 'create'])->name('createusers')->middleware('auth'); // Página de criação de usuários
Route::post('store/users', [UsersController::class, 'store'])->name('storeusers')->middleware('auth'); // Salvar novo usuário
Route::get('edit/users/{id}', [UsersController::class, 'edit'])->name('editusers')->middleware('auth'); // Editar usuário
Route::post('update/users/{id}', [UsersController::class, 'update'])->name('updateusers')->middleware('auth'); // Atualizar dados do usuário
Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('deleteuser'); // Excluir usuário

// Clientes potenciais
Route::get('clientes/potencial', [ClientesPotencialController::class, 'index'])->name('clientespotencial')->middleware('auth'); // Lista de clientes potenciais
Route::get('desativarclientes/potencial/{id}', [ClientesPotencialController::class, 'desativar'])->name('desativarclientespotencial')->middleware('auth'); // Desativar cliente potencial
Route::get('ativar/clientes/potencial/{id}', [ClientesPotencialController::class, 'ativar'])->name('ativarclientespotencial')->middleware('auth'); // Ativar cliente potencial

// Grupo de empresas
Route::get('grupo', [\App\Http\Controllers\Admin\EmpresaController::class, 'empresa'])->middleware('auth'); // Lista de grupos de empresas

// Perfil do usuário
Route::get('perfil', [UsersController::class, 'perfil'])->name('perfil')->middleware('auth'); // Página de perfil do usuário
Route::post('update/profile/', [UsersController::class, 'updateprofile'])->name('updateprofile')->middleware('auth'); // Atualizar perfil do usuário
Route::post('update/password/', [UsersController::class, 'updatepassword'])->name('updatepassword')->middleware('auth'); // Atualizar senha do usuário

// Clientes
Route::get('clientes', [ClienteController::class, 'index'])->name('clientes')->middleware('auth'); // Lista de clientes
Route::get('edit/clientes/{id}', [ClienteController::class, 'edit'])->name('editclientes')->middleware('auth'); // Editar cliente
Route::post('/update/clientes/{id}', [ClienteController::class, 'update'])->name('updateclientes')->middleware('auth'); // Atualizar cliente

// Mensagens
Route::get('mensagem', [MensagemController::class, 'index'])->name('mensagem')->middleware('auth'); // Lista de mensagens
Route::get('create/mensagem', [MensagemController::class, 'create'])->name('createmensagem')->middleware('auth'); // Criar nova mensagem
Route::post('store/mensagem', [MensagemController::class, 'store'])->name('storemensagem')->middleware('auth'); // Salvar nova mensagem
Route::get('edit/mensagem/{id}', [MensagemController::class, 'edit'])->name('editmensagem')->middleware('auth'); // Editar mensagem
Route::post('update/mensagem/{id}', [MensagemController::class, 'update'])->name('updatemensagem')->middleware('auth'); // Atualizar mensagem
Route::delete('/mensagem/{id}', [MensagemController::class, 'destroy'])->name('deleteMensagem'); // Excluir mensagem

// Ciclos
Route::get('ciclus', [CiclusController::class, 'index'])->name('ciclus')->middleware('auth'); // Lista de ciclos
Route::get('create/ciclus', [CiclusController::class, 'create'])->name('createciclus')->middleware('auth'); // Criar novo ciclo
Route::post('store/ciclus', [CiclusController::class, 'store'])->name('storecicuos')->middleware('auth'); // Salvar novo ciclo
Route::get('edit/ciclus/{id}', [CiclusController::class, 'edit'])->name('editciculos')->middleware('auth'); // Editar ciclo
Route::get('update/ciclus/{id}', [CiclusController::class, 'update'])->name('updateciculos')->middleware('auth'); // Atualizar ciclo
Route::delete('/ciclus/{id}', [CiclusController::class, 'destroy'])->name('deleteciclus'); // Excluir ciclo

// Configuração de email
Route::get('/mail-config', [\App\Http\Controllers\Admin\EmailController::class, 'showMailConfig'])->name('showMailConfig'); // Página de configuração de email
Route::post('/mail-config/store', [\App\Http\Controllers\Admin\EmailController::class, 'storeMailConfig'])->name('storeEmailConfig'); // Salvar configuração de email

// Envio de mensagens
Route::get('/enviar-email', [\App\Http\Controllers\Admin\EnviarMensagemController::class, 'email'])->name('enviar.email'); // Enviar email
Route::get('/execute-python-scripts', [\App\Http\Controllers\Admin\PythonController::class, 'executePythonScripts']); // Executar scripts Python

// WhatsApp
Route::get('/teste', [\App\Http\Controllers\Admin\WhatsappController::class, 'teste']); // Testar envio de mensagens no WhatsApp
Route::middleware('api')
  ->get('/send-whatsapp', [\App\Http\Controllers\Admin\WhatsappController::class, 'sendMessage'])
  ->name('sendwhatsap');

Route::get('/whatsapp-qrcode', [\App\Http\Controllers\Admin\WhatsAppController::class, 'showQRCode'])->name('whatsapp.qrcode');
Route::get('/receive-qr', [\App\Http\Controllers\Admin\WhatsAppController::class, 'storeQRCode']);
