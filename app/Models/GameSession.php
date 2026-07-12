<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    protected $fillable = [
        'theme_id',
        'game_type',
        'score',
        'hits',
        'mistakes',
        'accuracy',
        'duration',
        'level_reached',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'accuracy' => 'float',
    ];

    /**
     * Relación con tema
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    /**
     * Calcular precisión
     */
    public function calculateAccuracy(): float
    {
        if ($this->hits + $this->mistakes === 0) {
            return 0;
        }

        return round(($this->hits / ($this->hits + $this->mistakes)) * 100, 2);
    }
}
