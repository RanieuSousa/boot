<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EnviarMensagemController;


Route::post('/receive-qr', [\App\Http\Controllers\Admin\WhatsAppController::class, 'storeQRCode']);
