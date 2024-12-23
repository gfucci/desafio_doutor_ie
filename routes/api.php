<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LivrosController;
use App\Http\Controllers\LivrosImportacoesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/v1/auth/register', [AuthController::class, 'register']);
Route::post('/v1/auth/token', [AuthController::class, 'token']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('v1/livros', LivrosController::class)->only('store', 'index');
    Route::post('v1/livros/{livroId}/importar-indices-xml', [LivrosImportacoesController::class, 'importarIndicesXml'])->name('import_livro');
});