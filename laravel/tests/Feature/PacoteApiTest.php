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

    public function test_can_create_a_pacote()
    {
        $data = [
            'nome' => 'Pacote de Teste',
            'descricao' => 'Descrição do pacote de teste',
        ];

        $response = $this->postJson('/api/pacotes', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('pacotes', $data);
    }

    public function test_can_show_a_pacote()
    {
        $pacote = Pacote::factory()->create();

        $response = $this->getJson('/api/pacotes/' . $pacote->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['nome' => $pacote->nome]);
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
