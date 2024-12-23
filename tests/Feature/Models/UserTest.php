<?php

namespace Tests\Feature\Models;

use App\Models\Livro;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $user = User::factory()->create();
        $livro = Livro::factory()->create(['usuario_publicador_id' => $user->id]);

        foreach ($user->livros as $livro) {
            $this->assertInstanceOf(Livro::class, $livro);
            $this->assertEquals(
                $livro->usuario_publicador_id, 
                $user->id
            );
        }

        $this->assertInstanceOf(HasMany::class, $user->livros());
    }
}
