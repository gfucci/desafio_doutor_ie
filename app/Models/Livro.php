<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Livro extends Model
{
    use HasFactory;

    protected $table = 'livros';
    protected $fillable = [
        'titulo',
        'usuario_publicador_id'
    ];

    public function publicador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_publicador_id');
    }

    public function indices(): HasMany
    {
        return $this->hasMany(Indice::class, 'livro_id');
    }
}
