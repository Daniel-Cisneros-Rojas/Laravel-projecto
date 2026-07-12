<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'background_image',
        'color_primary',
        'color_secondary',
    ];

    /**
     * Relación con caracteres
     */
    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    /**
     * Relación con sesiones de juego
     */
    public function gameSessions(): HasMany
    {
        return $this->hasMany(GameSession::class);
    }

    /**
     * Obtener caracteres por nivel
     */
    public function charactersByLevel(int $level)
    {
        return $this->characters()->where('level', '<=', $level)->get();
    }
}
