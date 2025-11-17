<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GerarImpressaoRequest;
use App\Services\ImpressaoService;
use Barryvdh\DomPDF\Facade\Pdf;

class ImpressaoController extends Controller
{
    public function gerarPdf(GerarImpressaoRequest $request, ImpressaoService $impressaoService)
    {
        $validated = $request->validated();

        $paginas = $impressaoService->agruparParaImpressao(
            $validated['exames'] ?? [],
            $validated['pacotes'] ?? []
        );

        if (empty($paginas)) {
            return response()->json([
                'message' => 'Nenhum exame ou pacote válido foi fornecido para impressão.'
            ], 400);
        }

        $dadosMockados = [
            'paciente' => 'José da Silva (Mock)',
            'medico' => 'Dr(a). Maria Oliveira (Mock)',
            'data' => now()->format('d/m/Y')
        ];

        $pdf = Pdf::loadView('pdf_view', [
            'paginas' => $paginas,
            'dadosMockados' => $dadosMockados
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('solicitacao_exames.pdf');
    }
}