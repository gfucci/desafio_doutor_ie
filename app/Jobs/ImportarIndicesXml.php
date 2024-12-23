<?php

namespace App\Jobs;

use App\Models\Indice;
use App\Models\Livro;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use SimpleXMLElement;

class ImportarIndicesXml implements ShouldQueue
{
    use Queueable;

    private Livro $livro;
    private $xmlData;

    

    /**
     * Create a new job instance.
     */
    public function __construct(Livro $livro, $xmlData)
    {
        $this->livro = $livro;
        $this->xmlData = $xmlData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $xml = new SimpleXMLElement($this->xmlData);
        $this->importaIndices($xml->indice);
    }

    private function importaIndices($indice, $parentId = null)
    {
        $newIndice = Indice::create([
            'livro_id' => $this->livro->id,
            'titulo' => (string) $indice->titulo,
            'pagina' => (int) $indice->pagina,
            'indice_pai_id' => $parentId,
        ]);

        if (isset($indice->item)) {
            foreach ($indice->item as $item) {
                $this->importaIndices($item, $newIndice->id);
            }
        }
    }
}
