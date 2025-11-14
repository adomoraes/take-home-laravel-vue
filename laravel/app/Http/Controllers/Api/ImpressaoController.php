<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GerarImpressaoRequest; // Nosso Request de validação
use App\Services\ImpressaoService; // Nosso Service de lógica

// --- ESTA É A CORREÇÃO ---
// Em vez de 'use PDF;', usamos o namespace completo da classe.
use Barryvdh\DomPDF\Facade\Pdf;
// --------------------------

class ImpressaoController extends Controller
{
    /**
     * Recebe os IDs de exames/pacotes, agrupa-os e gera um PDF
     *
     * POST /api/gerar-impressao
     */
    public function gerarPdf(GerarImpressaoRequest $request, ImpressaoService $impressaoService)
    {
        // 1. Os dados já foram validados pelo GerarImpressaoRequest
        $validated = $request->validated();

        // 2. Usar o nosso Service para agrupar os dados
        $paginas = $impressaoService->agruparParaImpressao(
            $validated['exames'] ?? [],
            $validated['pacotes'] ?? []
        );

        // 3. Verificar se há algo para imprimir
        if (empty($paginas)) {
            return response()->json([
                'message' => 'Nenhum exame ou pacote válido foi fornecido para impressão.'
            ], 400); // 400 Bad Request
        }

        // 4. Mockar os dados de paciente e médico (como pedido no README)
        $dadosMockados = [
            'paciente' => 'José da Silva (Mock)',
            'medico' => 'Dr(a). Maria Oliveira (Mock)',
            'data' => now()->format('d/m/Y')
        ];

        // 5. Carregar a View Blade e passar os dados para ela
        // Note que agora usamos a Facade correta 'Pdf' (P maiúsculo)
        $pdf = Pdf::loadView('pdf_view', [
            'paginas' => $paginas,
            'dadosMockados' => $dadosMockados
        ]);

        // 6. Definir o tamanho do papel (opcional, mas recomendado)
        $pdf->setPaper('a4', 'portrait'); // retrato

        // 7. Transmitir o PDF para o browser
        return $pdf->stream('solicitacao_exames.pdf');
    }
}