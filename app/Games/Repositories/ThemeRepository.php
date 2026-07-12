<?php

namespace App\Games\Repositories;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Collection;

class ThemeRepository
{
    /**
     * Obtener todos los temas
     */
    public function getAll(): Collection
    {
        return Theme::all();
    }

    /**
     * Obtener tema por ID
     */
    public function getById(int $id): ?Theme
    {
        return Theme::find($id);
    }

    /**
     * Obtener tema por slug
     */
    public function getBySlug(string $slug): ?Theme
    {
        return Theme::where('slug', $slug)->first();
    }

    /**
     * Obtener tema con sus caracteres
     */
    public function getWithCharacters(int $id): ?Theme
    {
        return Theme::with('characters')->find($id);
    }

    /**
     * Contar total de temas
     */
    public function count(): int
    {
        return Theme::count();
    }

    /**
     * Verificar si existe un tema
     */
    public function exists(int $id): bool
    {
        return Theme::where('id', $id)->exists();
    }
}
