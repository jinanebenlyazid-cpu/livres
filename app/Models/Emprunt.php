<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emprunt extends Model
{
    use HasFactory;

    public const STATUT_EN_COURS = 'En cours';
    public const STATUT_RETOURNE = 'Retourné';
    public const STATUT_EN_RETARD = 'En retard';

    protected $fillable = [
        'livre_id',
        'user_id',
        'date_emprunt',
        'date_retour_prevue',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date_emprunt' => 'date',
            'date_retour_prevue' => 'date',
        ];
    }

    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
