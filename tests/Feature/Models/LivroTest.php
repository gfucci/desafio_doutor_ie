<?php

namespace Tests\Feature\Models;

use App\Models\Indice;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LivroTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_publicador_relationship(): void
    {
        // Cria um usuário e um livro relacionado a ele
        $livroProvider = $this->livroProvider();

        $this->assertInstanceOf(User::class, $livroProvider['livro']->publicador);
        $this->assertInstanceOf(BelongsTo::class, $livroProvider['livro']->publicador());
        $this->assertEquals(
            $livroProvider['user']->id, 
            $livroProvider['livro']->publicador->id
        );
    }

    public function test_indices_relationship(): void
    {
        $livroProvider = $this->livroProvider();

        foreach ($livroProvider['livro']->indices as $indice) {
            $this->assertInstanceOf(Indice::class, $indice);
            $this->assertEquals(
                $indice->livro_id, 
                $livroProvider['livro']->id
            );
        }

        $this->assertInstanceOf(HasMany::class, $livroProvider['livro']->indices());
        
    }

    private function livroProvider()
    {
        $user = User::factory()->create();
        $livro = Livro::factory()->create(['usuario_publicador_id' => $user->id]);
        $indice = Indice::factory()->create(['livro_id' => $livro->id]);

        return [
            'user' => $user,
            'livro' => $livro,
            'indice' => $indice
        ];
    }
}
