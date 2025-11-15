<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Pacote;
use App\Models\Exame;

class PacoteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_pacotes()
    {
        Pacote::factory()->count(3)->create();

        $response = $this->getJson('/api/pacotes');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /**
     * Testa se podemos criar um pacote com exames válidos.
     *
     * @return void
     */
    public function test_can_create_a_pacote(): void
    {
        // 1. Setup: Precisamos de exames que existam no banco de dados
        $exames = Exame::factory()->count(2)->create();

        // 2. Dados: Preparamos o payload (carga útil) válido
        $data = [
            'name' => 'Pacote de Teste Automatizado',
            'observations' => 'Observações do teste.',
            'exams' => $exames->pluck('id')->toArray(), // Pegamos os IDs [1, 2]
        ];

        // 3. Ação: Fazemos o pedido
        $response = $this->postJson('/api/pacotes', $data);

        // 4. Asserção (Assert): Verificamos se a resposta está correta
        // Esta é a linha 33, que agora deve passar
        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Pacote de Teste Automatizado']);

        // 5. Asserção (Assert) do Banco de Dados
        // Verificamos se o pacote foi salvo na tabela 'pacotes'
        $this->assertDatabaseHas('pacotes', [
            'name' => 'Pacote de Teste Automatizado'
        ]);

        // Verificamos se as relações foram salvas na tabela pivot 'exame_pacote'
        $pacoteId = $response->json('id'); // Pega o ID do pacote criado
        $this->assertDatabaseHas('exame_pacote', [
            'pacote_id' => $pacoteId,
            'exame_id' => $exames[0]->id,
        ]);
        $this->assertDatabaseHas('exame_pacote', [
            'pacote_id' => $pacoteId,
            'exame_id' => $exames[1]->id,
        ]);
    }

    public function test_can_show_a_pacote()
    {
        $pacote = Pacote::factory()->create();

        $response = $this->getJson('/api/pacotes/' . $pacote->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $pacote->name]);
    }

    public function test_can_update_a_pacote()
    {
        $pacote = Pacote::factory()->create();

        $data = [
            'nome' => 'Pacote Atualizado',
            'descricao' => 'Descrição atualizada',
        ];

        $response = $this->putJson('/api/pacotes/' . $pacote->id, $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('pacotes', $data);
    }

    public function test_can_delete_a_pacote()
    {
        $pacote = Pacote::factory()->create();

        $response = $this->deleteJson('/api/pacotes/' . $pacote->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('pacotes', ['id' => $pacote->id]);
    }

    public function test_can_add_exames_to_pacote()
    {
        $pacote = Pacote::factory()->create();
        $exames = Exame::factory()->count(3)->create();
        $exameIds = $exames->pluck('id')->toArray();

        $response = $this->postJson('/api/pacotes/' . $pacote->id . '/exames', [
            'exames' => $exameIds,
        ]);

        $response->assertStatus(200);

        foreach ($exameIds as $exameId) {
            $this->assertDatabaseHas('exame_pacote', [
                'pacote_id' => $pacote->id,
                'exame_id' => $exameId,
            ]);
        }
    }
}
