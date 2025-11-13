<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exame; // Importe o Model
use App\Http\Requests\StoreExameRequest; // Importe o Request
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Para usar os códigos de status HTTP

class ExameController extends Controller
{
    /**
     * Listar todos os exames.
     * GET /api/exames
     */
    public function index()
    {
        // Retorna todos os exames da tabela
        return Exame::all();
    }

    /**
     * Criar um novo exame.
     * POST /api/exames
     */
    public function store(StoreExameRequest $request)
    {
        // A validação já foi feita automaticamente pelo StoreExameRequest
        // $request->validated() retorna um array apenas com os dados validados
        $exame = Exame::create($request->validated());

        // Retorna o novo exame criado e o status 201 (Created)
        return response()->json($exame, Response::HTTP_CREATED);
    }

    /**
     * Mostrar um exame específico.
     * GET /api/exames/{id}
     */
    public function show(Exame $exame)
    {
        // O Laravel automaticamente encontra o exame pelo ID (Route Model Binding)
        // Se não encontrar, ele já retorna um 404 Not Found automaticamente.
        return $exame;
    }

    /**
     * Atualizar um exame existente.
     * PUT/PATCH /api/exames/{id}
     */
    public function update(StoreExameRequest $request, Exame $exame)
    {
        // Reutilizamos o mesmo StoreExameRequest para garantir que os dados
        // de atualização também sejam válidos.
        // O Route Model Binding encontra o $exame a ser atualizado.
        
        $exame->update($request->validated());

        // Retorna o exame atualizado
        return response()->json($exame);
    }

    /**
     * Apagar um exame.
     * DELETE /api/exames/{id}
     */
    public function destroy(Exame $exame)
    {
        // O Route Model Binding encontra o $exame a ser apagado.
        $exame->delete();

        // Retorna uma resposta vazia com status 204 (No Content)
        return response()->noContent();
    }
}