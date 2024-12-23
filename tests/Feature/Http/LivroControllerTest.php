<?php

namespace Tests\Feature\Http;

use App\Models\Indice;
use App\Models\Livro;
use App\Models\User;
use Database\Factories\IndiceFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LivroControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_store_livros(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'titulo' => 'Livro de Teste',
            'usuario_publicador_id' => $user->id,
            'indices' => [
                [
                    'titulo' => 'Capítulo 1',
                    'pagina' => 1,
                    'subindices' => [
                        [
                            'titulo' => 'indice 1.1',
                            'pagina' => 3,
                            'subindices' => []
                        ],
                    ],
                ],
                [
                    'titulo' => 'indice 2',
                    'pagina' => 4,
                    'subindices' => []
                ]
            ]
        ];

        $response = $this->postJson('api/v1/livros', $data);
        $response->assertStatus(201);

        $livro = Livro::latest()->first();
        $indices = $livro->indices;

        foreach ($indices as $indice) {
            $this->assertDatabaseHas(
                'indices', 
                ['titulo' => $indice->titulo, 'livro_id' => $livro->id, 'indice_pai_id' => $indice->indice_pai_id]
            );
        }
    }
}
