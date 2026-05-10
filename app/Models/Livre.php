<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livre extends Model
{
    use HasFactory;

    public const STATUT_DISPONIBLE = 'Disponible';
    public const STATUT_INDISPONIBLE = 'Indisponible';

    protected $fillable = [
        'titre',
        'auteur',
        'isbn',
        'image',
        'nombre_exemplaires',
        'statut',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function emprunts(): HasMany
    {
        return $this->hasMany(Emprunt::class);
    }

    public function isAvailable(): bool
    {
        return $this->statut === self::STATUT_DISPONIBLE && $this->nombre_exemplaires > 0;
    }
}
