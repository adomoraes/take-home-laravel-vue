<?php

namespace App\Services;

use App\Models\Exame;
use App\Models\Pacote;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

class ImpressaoService
{
    /**
     * Agrupa exames e pacotes para a impressão, separando por "grupo" de exame.
     *
     * @param array $exameIds IDs de exames avulsos.
     * @param array $pacoteIds IDs de pacotes.
     * @return array Estrutura de dados agrupada por página (grupo) e depois por pacote.
     */
    public function agruparParaImpressao(array $exameIds = [], array $pacoteIds = []): array
    {
        $paginas = [];

        if (!empty($exameIds)) {
            $examesAvulsos = Exame::findMany($exameIds);

            foreach ($examesAvulsos as $exame) {
                $paginaKey = $exame->group;
                $pacoteKey = 'Exames avulsos';

                $paginas[$paginaKey][$pacoteKey][] = $exame;
            }
        }

        if (!empty($pacoteIds)) {
            $pacotesSelecionados = Pacote::with('exames')->findMany($pacoteIds);

            foreach ($pacotesSelecionados as $pacote) {
                $pacoteKey = $pacote->name;

                foreach ($pacote->exames as $exame) {
                    $paginaKey = $exame->group;

                    $paginas[$paginaKey][$pacoteKey][] = $exame;
                }
            }
        }
        
        ksort($paginas);

        return $paginas;
    }

    /**
     * Gera o PDF da solicitação de exames.
     *
     * @param array $paginas
     * @param array $dadosMockados
     * @return \Illuminate\Http\Response
     */
    public function gerarPdf(array $paginas, array $dadosMockados)
    {
        $pdf = Pdf::loadView('pdf_view', [
            'paginas' => $paginas,
            'dadosMockados' => $dadosMockados
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('solicitacao_exames.pdf');
    }
}