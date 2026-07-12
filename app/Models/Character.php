<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    protected $fillable = [
        'theme_id',
        'hanzi',
        'pinyin',
        'meaning',
        'level',
        'image',
        'audio',
        'example_sentence',
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    /**
     * Relación con tema
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }
}
