<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pacote; // Importe o Model
use App\Http\Requests\StorePacoteRequest; // Importe o Request de Store
use App\Http\Requests\AddExamesToPacoteRequest; // Importe o Request de Adicionar Exames
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PacoteController extends Controller
{
    /**
     * Listar todos os pacotes.
     * GET /api/pacotes
     */
    public function index()
    {
        // Usamos 'with' para carregar os exames relacionados (Eager Loading)
        // Isto evita o problema de N+1 queries e melhora a performance.
        return Pacote::with('exames')->get();
    }

    /**
     * Criar um novo pacote.
     * POST /api/pacotes
     */
    public function store(StorePacoteRequest $request)
    {
        $validated = $request->validated();

        // 1. Criar o pacote com os dados principais
        $pacote = Pacote::create([
            'name' => $validated['name'],
            'observations' => $validated['observations'] ?? null,
        ]);

        // 2. Anexar (attach) os IDs dos exames ao pacote na tabela pivot
        if (!empty($validated['exams'])) {
            $pacote->exames()->attach($validated['exams']);
        }

        // 3. Retornar o pacote criado, carregando a relação de exames
        return response()->json($pacote->load('exames'), Response::HTTP_CREATED);
    }

    /**
     * Mostrar um pacote específico.
     * GET /api/pacotes/{id}
     */
    public function show(Pacote $pacote)
    {
        // O Route Model Binding já encontra o pacote.
        // Apenas carregamos a relação de exames antes de retornar.
        return $pacote->load('exames');
    }

    /**
     * Atualizar um pacote existente.
     * PUT/PATCH /api/pacotes/{id}
     */
    public function update(StorePacoteRequest $request, Pacote $pacote)
    {
        $validated = $request->validated();

        // 1. Atualizar os dados principais do pacote
        $pacote->update([
            'name' => $validated['name'],
            'observations' => $validated['observations'] ?? null,
        ]);

        // 2. Sincronizar os exames.
        // 'sync' é ideal para updates. Ele remove os exames que não estão
        // no array, adiciona os novos e mantém os que já estavam.
        if (isset($validated['exams'])) {
            $pacote->exames()->sync($validated['exams']);
        }

        // 3. Retornar o pacote atualizado, carregando a relação
        return response()->json($pacote->load('exames'));
    }

    /**
     * Apagar um pacote.
     * DELETE /api/pacotes/{id}
     */
    public function destroy(Pacote $pacote)
    {
        // O Route Model Binding encontra o pacote.
        // As entradas na tabela pivot 'exame_pacote' serão removidas
        // automaticamente graças ao 'onDelete('cascade')' da migration.
        $pacote->delete();

        return response()->noContent();
    }
    
    // --- Métodos Personalizados ---

    /**
     * Adiciona exames a um pacote existente sem remover os atuais.
     * POST /api/pacotes/{pacote}/exames
     */
    public function adicionarExames(AddExamesToPacoteRequest $request, Pacote $pacote)
    {
        $validated = $request->validated();

        // 'syncWithoutDetaching' adiciona os IDs do array
        // sem remover (detaching) os que já estavam associados.
        // É perfeito para "adicionar mais" exames.
        $pacote->exames()->syncWithoutDetaching($validated['exams']);

        return $pacote->load('exames');
    }
}