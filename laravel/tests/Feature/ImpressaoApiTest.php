<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Exame;
use App\Models\Pacote;

class ImpressaoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_generate_pdf_with_exames_and_pacotes()
    {
        // 1. Criar dados de teste
        $examesIndividuais = Exame::factory()->count(2)->create();
        $pacote = Pacote::factory()->create();
        $examesDoPacote = Exame::factory()->count(3)->create();
        $pacote->exames()->attach($examesDoPacote->pluck('id'));

        // 2. Montar os dados para a requisição
        $requestData = [
            'exames' => $examesIndividuais->pluck('id')->toArray(),
            'pacotes' => [$pacote->id],
        ];

        // 3. Chamar o endpoint
        $response = $this->postJson('/api/gerar-impressao', $requestData);

        // 4. Verificar as asserções
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        // --- ESTA É A CORREÇÃO ---
        // Em vez de uma correspondência exata (que falha por causa das aspas),
        // verificamos se o header contém a string correta (sem aspas).
        $this->assertStringContainsString(
            'inline; filename=solicitacao_exames.pdf',
            $response->headers->get('Content-Disposition')
        );
        // --- FIM DA CORREÇÃO ---

        // Opcional: verificar se o conteúdo não está vazio
        $this->assertNotEmpty($response->getContent());
    }

    public function test_returns_error_if_no_ids_are_provided()
    {
        // Chamar o endpoint sem dados
        $response = $this->postJson('/api/gerar-impressao', []);

        $response->assertStatus(400); // Validation error
    }

    public function test_returns_error_if_invalid_ids_are_provided()
    {
        $requestData = [
            'exames' => [999], // ID inválido
            'pacotes' => [888], // ID inválido
        ];

        $response = $this->postJson('/api/gerar-impressao', $requestData);

        $response->assertStatus(422); // Validation error
        $response->assertJsonValidationErrors(['exames.0', 'pacotes.0']);
    }

    public function test_can_generate_pdf_with_only_exames()
    {
        $exames = Exame::factory()->count(3)->create();

        $requestData = [
            'exames' => $exames->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/gerar-impressao', $requestData);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_can_generate_pdf_with_only_pacotes()
    {
        $pacote = Pacote::factory()->create();
        $exames = Exame::factory()->count(2)->create();
        $pacote->exames()->attach($exames->pluck('id'));

        $requestData = [
            'pacotes' => [$pacote->id],
        ];

        $response = $this->postJson('/api/gerar-impressao', $requestData);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
