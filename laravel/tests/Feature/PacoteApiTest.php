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

    /**
     * Testa se podemos atualizar um pacote.
     *
     * @return void
     */
    public function test_can_update_a_pacote(): void
    {
        // 1. Setup: Criar dados originais
        $exameOriginal = Exame::factory()->create();

        $pacote = Pacote::create([
            'name' => 'Pacote Original',
            'observations' => 'Obs original.'
        ]);
        $pacote->exames()->attach($exameOriginal->id);

        // 2. Setup: Criar os *novos* dados que vamos enviar
        $novosExames = Exame::factory()->count(2)->create();

        // 3. Dados: Preparar o payload (carga útil) VÁLIDO para a atualização
        // Esta variável $data NÃO PODE estar vazia.
        $data = [
            'name' => 'Pacote Atualizado',
            'observations' => 'Observações atualizadas.',
            'exams' => $novosExames->pluck('id')->toArray(), // IDs [2, 3]
        ];

        // 4. Ação: Fazer o pedido PUT
        $response = $this->putJson('/api/pacotes/' . $pacote->id, $data);

        // 5. Asserção (Assert): Verificar a resposta
        // Esta é a linha 88, que agora deve passar
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Pacote Atualizado']);

        // 6. Asserção (Assert) do Banco de Dados
        // Verificamos se o nome do pacote mudou
        $this->assertDatabaseHas('pacotes', [
            'id' => $pacote->id,
            'name' => 'Pacote Atualizado'
        ]);

        // Verificamos se os novos exames foram associados
        $this->assertDatabaseHas('exame_pacote', [
            'pacote_id' => $pacote->id,
            'exame_id' => $novosExames[0]->id,
        ]);

        // Verificamos se o exame original foi removido (graças ao sync())
        $this->assertDatabaseMissing('exame_pacote', [
            'pacote_id' => $pacote->id,
            'exame_id' => $exameOriginal->id,
        ]);
    }

    public function test_can_delete_a_pacote()
    {
        $pacote = Pacote::factory()->create();

        $response = $this->deleteJson('/api/pacotes/' . $pacote->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('pacotes', ['id' => $pacote->id]);
    }
}
