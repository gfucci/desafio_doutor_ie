<?php

namespace App\Services;

use App\Models\Indice;

class SubindiceService
{
    public static function createSubindices(array $subindices, int $indicePaiId, int $livroId): void
    {
        foreach ($subindices as $subindice) {
            // Cria o subíndice e associa ao índice pai
            $newSubindice = Indice::create([
                'livro_id' => $livroId,
                'indice_pai_id' => $indicePaiId,
                'titulo' => $subindice['titulo'],
                'pagina' => $subindice['pagina'],
            ]);

            // Se houver mais subsubíndices, cria recursivamente
            if (!empty($subindice['subindices'])) {
                self::createSubindices($subindice['subindices'], $newSubindice->id, $livroId);
            }
        }
    }
}