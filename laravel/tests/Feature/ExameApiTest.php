<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Exame;

class ExameApiTest extends TestCase
{
    use RefreshDatabase; // Usa um banco de dados limpo para cada teste

    /**
     * Testa se um exame pode ser criado com sucesso via API.
     *
     * @return void
     */
    public function test_can_create_an_exame()
    {
        $exameData = [
            'name' => 'Raio-X do Tórax',
            'laterality' => 'AO',
            'comment' => 'Paciente com tosse persistente.',
            'group' => 'Grupo 1',
        ];

        // Faz a requisição POST para a API
        $response = $this->postJson('/api/exames', $exameData);

        // Verifica se a resposta foi 201 (Created)
        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Raio-X do Tórax']);

        // Verifica se o exame foi realmente salvo no banco de dados
        $this->assertDatabaseHas('exames', [
            'name' => 'Raio-X do Tórax'
        ]);
    }

    /**
     * Testa a falha de validação ao tentar criar um exame sem o campo 'name'.
     *
     * @return void
     */
    public function test_cannot_create_exame_with_invalid_data()
    {
        $exameData = [
            // 'name' está faltando
            'laterality' => 'OD',
            'group' => 'Grupo 2',
        ];

        $response = $this->postJson('/api/exames', $exameData);

        // Verifica se a resposta foi 422 (Unprocessable Entity)
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']); // Verifica se o erro é no campo 'name'
    }

    /**
     * Testa se a API pode listar os exames existentes.
     *
     * @return void
     */
    public function test_can_list_exames()
    {
        // Cria 3 exames de exemplo
        Exame::factory()->count(3)->create();

        $response = $this->getJson('/api/exames');

        // Verifica se a resposta foi 200 (OK)
        $response->assertStatus(200)
            ->assertJsonCount(3); // Verifica se a resposta contém 3 itens
    }

    /**
     * Testa se um exame pode ser atualizado.
     *
     * @return void
     */
    public function test_can_update_an_exame()
    {
        $exame = Exame::factory()->create();

        $updateData = [
            'name' => 'Ultrassonografia Abdominal',
            'group' => 'Grupo 3',
        ];

        $response = $this->putJson("/api/exames/{$exame->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Ultrassonografia Abdominal']);

        $this->assertDatabaseHas('exames', [
            'id' => $exame->id,
            'name' => 'Ultrassonografia Abdominal'
        ]);
    }

    /**
     * Testa se um exame pode ser deletado.
     *
     * @return void
     */
    public function test_can_delete_an_exame()
    {
        $exame = Exame::factory()->create();

        $response = $this->deleteJson("/api/exames/{$exame->id}");

        // Verifica se a resposta foi 204 (No Content)
        $response->assertStatus(204);

        // Verifica se o exame foi removido do banco de dados
        $this->assertDatabaseMissing('exames', [
            'id' => $exame->id
        ]);
    }
}
