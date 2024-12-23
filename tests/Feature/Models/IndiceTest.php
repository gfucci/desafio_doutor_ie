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

class IndiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_livro_relationship(): void
    {
        // Cria um usuário e um livro relacionado a ele
        $livroProvider = $this->livroProvider();

        $this->assertInstanceOf(Livro::class, $livroProvider['indice']->livro);
        $this->assertInstanceOf(BelongsTo::class, $livroProvider['indice']->livro());
        $this->assertEquals(
            $livroProvider['indice']->livro->id, 
            $livroProvider['livro']->id
        );
    }

    public function test_subIndices_relationship(): void
    {
        $livroProvider = $this->livroProvider();
        $indiceRaiz = $livroProvider['indice'];

        for ($i = 0; $i < 3; $i++) {
            Indice::factory()->create([
                'livro_id' => $livroProvider['livro']->id,
                'indice_pai_id' => $indiceRaiz->id,
            ]);
        }

        foreach ($livroProvider['indice']->subIndices as $subIndice) {
            $this->assertInstanceOf(Indice::class, $subIndice);
            $this->assertEquals(
                $subIndice->indice_pai_id, 
                $indiceRaiz->id
            );
        }

        $this->assertInstanceOf(HasMany::class, $livroProvider['indice']->subIndices());
    }

    public function test_indicePai_relationship(): void
    {
        // Cria um usuário e um livro relacionado a ele
        $livroProvider = $this->livroProvider();
        $indiceRaiz = $livroProvider['indice'];

        $subIndice = Indice::factory()->create([
            'livro_id' => $livroProvider['livro']->id,
            'indice_pai_id' => $indiceRaiz->id,
        ]);

        $this->assertInstanceOf(Indice::class, $subIndice->indicePai);
        $this->assertInstanceOf(BelongsTo::class, $subIndice->indicePai());
        $this->assertEquals(
            $subIndice->indicePai->id, 
            $indiceRaiz->id
        );
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
