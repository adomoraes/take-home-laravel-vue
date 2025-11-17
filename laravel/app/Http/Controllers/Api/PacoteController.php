<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pacote;
use App\Http\Requests\StorePacoteRequest;
use App\Http\Requests\AddExamesToPacoteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PacoteController extends Controller
{
    public function index(): JsonResponse
    {
        $pacotes = Pacote::with('exames')->get();
        return response()->json($pacotes);
    }

    public function store(StorePacoteRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $pacote = Pacote::create([
            'name' => $validated['name'],
            'observations' => $validated['observations'] ?? null,
        ]);

        if (!empty($validated['exams'])) {
            $pacote->exames()->attach($validated['exams']);
        }

        return response()->json($pacote->load('exames'), Response::HTTP_CREATED);
    }

    public function show(Pacote $pacote): JsonResponse
    {
        return response()->json($pacote->load('exames'));
    }

    public function update(StorePacoteRequest $request, Pacote $pacote): JsonResponse
    {
        $validated = $request->validated();

        $pacote->update([
            'name' => $validated['name'],
            'observations' => $validated['observations'] ?? null,
        ]);

        if (isset($validated['exams'])) {
            $pacote->exames()->sync($validated['exams']);
        }

        return response()->json($pacote->load('exames'));
    }

    public function destroy(Pacote $pacote): Response
    {
        $pacote->delete();

        return response()->noContent();
    }
    
    public function adicionarExames(AddExamesToPacoteRequest $request, Pacote $pacote): JsonResponse
    {
        $validated = $request->validated();

        $pacote->exames()->syncWithoutDetaching($validated['exams']);

        return response()->json($pacote->load('exames'));
    }
}