<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLivroRequest;
use App\Models\Indice;
use App\Models\Livro;
use App\Services\SubindiceService;
use Illuminate\Http\Request;

class LivrosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $titulo = $request->query('titulo');
        $tituloDoIndice = $request->query('titulo_do_indice');
        $query = Livro::query();

        if (!empty($titulo)) {
            $query->where('titulo', 'like', "%$titulo%");
        }

        if (!empty($tituloDoIndice)) {
            $query->whereHas('indices', function($subWhere) use ($tituloDoIndice) {
                $subWhere->where('titulo', 'like', "%$tituloDoIndice%");
            });
        }

        $livros = $query->with('indices.subindices')->get();

        if ($livros->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum livro encontrado!',
                'data' => null
            ]);
        }

        return response()->json([
            'message' => 'Livros retornados com sucesso!',
            'data' => $livros
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLivroRequest $request)
    {
        $validated = $request->validated();

        try {
            $livro = Livro::create([
                'titulo' => $validated['titulo'],
                'usuario_publicador_id' => $validated['usuario_publicador_id'],
            ]);
            
            $indices = $validated['indices'] ?? [];
            
            foreach ($indices as $indice) {
                // Cria o índice principal
                $newIndice = Indice::create([
                    'livro_id' => $livro->id,
                    'indice_pai_id' => null,
                    'titulo' => $indice['titulo'],
                    'pagina' => $indice['pagina'],
                ]);
                
                // Verifica e cria subíndices
                if (!empty($indice['subindices'])) {
                    SubindiceService::createSubindices($indice['subindices'], $newIndice->id, $livro->id);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['message' => 'Livro cadastrado com sucesso!'], 201);
    }
}
