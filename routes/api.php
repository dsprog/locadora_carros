<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get("/", function(){
    return response()->json(['message' => 'Esse endpoint esta indisponivel!'], 404);
});

Route::group(['prefix' => '/v1'], function()
{
    Route::get("/", function(){
        return response()->json(['message' => 'Bem vindo a versão 1.0 da nossa api!'], 200);
    });
    Route::apiResource('marca', App\Http\Controllers\Api\V1\MarcaController::class);
    Route::apiResource('modelo', App\Http\Controllers\Api\V1\ModeloController::class);
    Route::apiResource('carro', App\Http\Controllers\Api\V1\CarroController::class);
    Route::apiResource('cliente', App\Http\Controllers\Api\V1\ClienteController::class);
    Route::apiResource('locacao', App\Http\Controllers\Api\V1\LocacaoController::class);
});
