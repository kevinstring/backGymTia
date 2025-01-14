<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\gymController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('csrf-token', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});

Route::get('getUsuario','App\Http\Controllers\gymController@getUsuarios');
Route::get('getTipoInscripcion','App\Http\Controllers\gymController@getTipoInscripcion');
Route::post('guardarCliente','App\Http\Controllers\gymController@guardarCliente');
Route::get('getUsuariosInscritos','App\Http\Controllers\gymController@getUsuariosInscritos');
Route::post('actualizarCliente','App\Http\Controllers\gymController@actualizarCliente');
Route::post('/send-whatsapp-message', 'App\Http\Controllers\WhatsAppController@sendTemplateMessage');
