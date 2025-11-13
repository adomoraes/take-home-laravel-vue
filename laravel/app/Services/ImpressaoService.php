<?php

namespace App\Services;

use App\Models\Exame;
use App\Models\Pacote;
use Illuminate\Support\Collection;

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
        // A estrutura final será:
        // [
        //    'Grupo 1' => [
        //        'Exames avulsos' => [Exame, Exame],
        //        'Pacote Glaucoma' => [Exame, Exame]
        //    ],
        //    'Grupo 2' => [
        //        'Pacote Retina' => [Exame]
        //    ]
        // ]
        $paginas = [];

        // 1. Processar Exames Avulsos
        if (!empty($exameIds)) {
            $examesAvulsos = Exame::findMany($exameIds);

            foreach ($examesAvulsos as $exame) {
                $paginaKey = $exame->group; // ex: "Grupo 1"
                $pacoteKey = 'Exames avulsos'; // Nome do "pacote" para avulsos

                // Adiciona o exame à estrutura
                $paginas[$paginaKey][$pacoteKey][] = $exame;
            }
        }

        // 2. Processar Pacotes Selecionados
        if (!empty($pacoteIds)) {
            // Usamos Eager Loading 'with('exames')' para performance
            $pacotesSelecionados = Pacote::with('exames')->findMany($pacoteIds);

            foreach ($pacotesSelecionados as $pacote) {
                $pacoteKey = $pacote->name; // ex: "Pacote Glaucoma"

                // Iterar sobre os exames DENTRO do pacote
                foreach ($pacote->exames as $exame) {
                    $paginaKey = $exame->group; // ex: "Grupo 1"

                    // Adiciona o exame à estrutura, sob o nome do seu pacote
                    $paginas[$paginaKey][$pacoteKey][] = $exame;
                }
            }
        }
        
        // 3. Ordenar as páginas (chaves do array) para garantir
        // que 'Grupo 1' venha antes de 'Grupo 2', etc.
        ksort($paginas);

        return $paginas;
    }
}