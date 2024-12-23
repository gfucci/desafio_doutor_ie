<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Indice extends Model
{
    use HasFactory;

    protected $table = 'indices';
    protected $fillable = [
        'livro_id',
        'indice_pai_id',
        'titulo',
        'pagina'
    ];

    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class, 'livro_id');
    }

    public function subIndices(): HasMany
    {
        return $this->hasMany(Indice::class, 'indice_pai_id');
    }

    public function indicePai(): BelongsTo
    {
        return $this->belongsTo(Indice::class, 'indice_pai_id');
    }
}
