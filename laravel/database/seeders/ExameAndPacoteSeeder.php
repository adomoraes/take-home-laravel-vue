<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exame;
use App\Models\Pacote;

class ExameAndPacoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // --- Criar Exames ---

        // Grupo 1
        $exame1 = Exame::create(['name' => 'Acuidade Visual', 'group' => 'Grupo 1']);
        $exame2 = Exame::create(['name' => 'Tonometria', 'group' => 'Grupo 1']);
        $exame3 = Exame::create(['name' => 'Fundoscopia', 'group' => 'Grupo 1']);

        // Grupo 2
        $exame4 = Exame::create(['name' => 'Paquimetria', 'group' => 'Grupo 2']);
        $exame5 = Exame::create(['name' => 'Gonioscopia', 'group' => 'Grupo 2']);

        // Grupo 3
        $exame6 = Exame::create(['name' => 'Retinografia', 'group' => 'Grupo 3']);
        $exame7 = Exame::create(['name' => 'Mapeamento de Retina', 'group' => 'Grupo 3']);

        // --- Criar Pacotes ---

        $pacoteGlaucoma = Pacote::create(['name' => 'Pacote Glaucoma']);
        $pacoteRetina = Pacote::create(['name' => 'Pacote Retina']);
        $pacoteCompleto = Pacote::create(['name' => 'Check-up Completo']);

        // --- Associar Exames aos Pacotes ---

        // Pacote Glaucoma (Exames do Grupo 1 e 2)
        $pacoteGlaucoma->exames()->attach([
            $exame2->id, // Tonometria
            $exame3->id, // Fundoscopia
            $exame4->id, // Paquimetria
            $exame5->id, // Gonioscopia
        ]);

        // Pacote Retina (Exames do Grupo 3)
        $pacoteRetina->exames()->attach([
            $exame6->id, // Retinografia
            $exame7->id, // Mapeamento de Retina
        ]);

        // Pacote Completo (Todos os exames)
        $pacoteCompleto->exames()->attach([
            $exame1->id,
            $exame2->id,
            $exame3->id,
            $exame4->id,
            $exame5->id,
            $exame6->id,
            $exame7->id,
        ]);
    }
}
