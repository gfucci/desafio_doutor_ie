<?php

namespace App\Http\Controllers;

use App\Jobs\ImportarIndicesXml;
use App\Models\Livro;
use Illuminate\Http\Request;

class LivrosImportacoesController extends Controller
{
    public function importarIndicesXml(Request $request, string $livroId)
    {
        try {
            $livro = Livro::findOrFail($livroId);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Nenhum livro encontrado!'
            ], 400);
        }

        $xmlData = file_get_contents($request->file('xml')->getRealPath());

        ImportarIndicesXml::dispatch($livro, $xmlData)->onQueue('importacoes');
        return response()->json(['message' => 'Importação iniciada'], 202);
    }
}
