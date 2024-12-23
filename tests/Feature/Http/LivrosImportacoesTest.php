<?php

namespace Tests\Feature\Http;

use App\Jobs\ImportarIndicesXml;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LivrosImportacoesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    // public function test_controller_is_dispatchable(): void
    // {
    //     Sanctum::actingAs(User::factory()->create());
    //     Queue::fake();

    //     $xmlProvider = $this->createXmlData();
        
    //     $response = $this->postJson(
    //         route('import_livro', ['livroId' => $xmlProvider['livro']->id]), [
    //             'xml' => new UploadedFile($xmlProvider['filePath'], 'temp_import.xml', 'application/xml', null, true),
    //         ]
    //     );

    //     $response->assertStatus(202);
    //     Queue::assertCount(1);
    //     Queue::assertPushedOn('importacoes', ImportarIndicesXml::class);
    // }

    public function test_logic_from_importar_indices()
    {
        $xmlProvider = $this->createXmlData();
        $xmlData = file_get_contents($xmlProvider['filePath']);
        
        ImportarIndicesXml::dispatchSync($xmlProvider['livro'], $xmlData);
        $this->assertDatabaseHas('indices', ['titulo' => 'Capítulo 1']);
    }

    //provider
    private function createXmlData() 
    {
        $livro = Livro::factory()->create();
        $xmlData = '<?xml version="1.0" encoding="UTF-8"?>
        <livro>
            <indice>
                <item pagina="1" titulo="Capítulo 1" />
                </item>
            </indice>
            <indice>
                <item pagina="7" titulo="Capítulo 2.1" />
            </indice>
        </livro>';

        Storage::fake('local');
        $filePath = storage_path('app/temp_import.xml');
        file_put_contents($filePath, $xmlData);

        return [
            'livro' => $livro,
            'filePath' => $filePath
        ];
    }
}
