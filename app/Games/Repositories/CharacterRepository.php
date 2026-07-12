<?php

namespace App\Games\Repositories;

use App\Games\DTOs\CharacterDTO;
use App\Models\Character;
use Illuminate\Database\Eloquent\Collection;

class CharacterRepository
{
    /**
     * Obtener todos los caracteres de un tema
     */
    public function getByTheme(int $themeId): Collection
    {
        return Character::where('theme_id', $themeId)->get();
    }

    /**
     * Obtener caracteres de un tema por nivel máximo
     */
    public function getByThemeAndMaxLevel(int $themeId, int $maxLevel): Collection
    {
        return Character::where('theme_id', $themeId)
            ->where('level', '<=', $maxLevel)
            ->get();
    }

    /**
     * Obtener caracteres de un tema por nivel exacto
     */
    public function getByThemeAndLevel(int $themeId, int $level): Collection
    {
        return Character::where('theme_id', $themeId)
            ->where('level', $level)
            ->get();
    }

    /**
     * Obtener carácter por ID
     */
    public function getById(int $id): ?Character
    {
        return Character::find($id);
    }

    /**
     * Obtener caracteres de un tema aleatorios
     */
    public function getRandomByTheme(int $themeId, int $limit = 5): Collection
    {
        return Character::where('theme_id', $themeId)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Obtener caracteres de un tema aleatorios hasta un nivel
     */
    public function getRandomByThemeAndMaxLevel(int $themeId, int $maxLevel, int $limit = 5): Collection
    {
        return Character::where('theme_id', $themeId)
            ->where('level', '<=', $maxLevel)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Contar caracteres de un tema
     */
    public function countByTheme(int $themeId): int
    {
        return Character::where('theme_id', $themeId)->count();
    }

    /**
     * Verificar si un carácter pertenece a un tema
     */
    public function belongsToTheme(int $characterId, int $themeId): bool
    {
        return Character::where('id', $characterId)
            ->where('theme_id', $themeId)
            ->exists();
    }

    /**
     * Convertir colección a DTOs
     */
    public function toDTO(Character $character): CharacterDTO
    {
        return CharacterDTO::fromModel($character);
    }
}
