<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExameController;
use App\Http\Controllers\Api\PacoteController;
use App\Http\Controllers\Api\ImpressaoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui é onde pode registar rotas de API para a sua aplicação.
| Estas rotas são carregadas pelo RouteServiceProvider e todas elas
| serão atribuídas ao grupo de middleware "api".
|
*/

// Rotas de autenticação (se necessário no futuro)
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// --- Rotas para Exames ---
// Esta linha cria automaticamente os endpoints:
// GET /exames, POST /exames, GET /exames/{id}, PUT/PATCH /exames/{id}, DELETE /exames/{id}
Route::apiResource('exames', ExameController::class);


// --- Rotas para Pacotes ---
// Cria os endpoints de CRUD padrão para pacotes
Route::apiResource('pacotes', PacoteController::class);

// Rota personalizada para adicionar múltiplos exames a um pacote de uma só vez
// Ex: POST /api/pacotes/5/exames (com um array de IDs de exames no body)
Route::post('pacotes/{pacote}/exames', [PacoteController::class, 'adicionarExames']);

// Endpoint que recebe os exames/pacotes selecionados e retorna um PDF
Route::post('gerar-impressao', [ImpressaoController::class, 'gerarPdf']);

// Poderíamos também adicionar uma rota para remover exames, se necessário:
// Route::delete('pacotes/{pacote}/exames', [PacoteController::class, 'removerExames']);