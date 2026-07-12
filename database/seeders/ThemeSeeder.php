<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            [
                'name' => 'Animales',
                'slug' => 'animales',
                'description' => 'Aprende caracteres relacionados con animales y criaturas',
                'color_primary' => '#f59e0b',
                'color_secondary' => '#fcd34d',
            ],
            [
                'name' => 'Profesiones',
                'slug' => 'profesiones',
                'description' => 'Caracteres de diferentes profesiones y oficios',
                'color_primary' => '#06b6d4',
                'color_secondary' => '#cffafe',
            ],
            [
                'name' => 'Comida',
                'slug' => 'comida',
                'description' => 'Caracteres relacionados con alimentos y bebidas',
                'color_primary' => '#ec4899',
                'color_secondary' => '#fbcfe8',
            ],
            [
                'name' => 'Naturaleza',
                'slug' => 'naturaleza',
                'description' => 'Caracteres de la naturaleza y elementos naturales',
                'color_primary' => '#10b981',
                'color_secondary' => '#d1fae5',
            ],
            [
                'name' => 'Familia',
                'slug' => 'familia',
                'description' => 'Caracteres relacionados con miembros de la familia',
                'color_primary' => '#8b5cf6',
                'color_secondary' => '#ede9fe',
            ],
            [
                'name' => 'Escuela',
                'slug' => 'escuela',
                'description' => 'Caracteres del ambiente escolar y académico',
                'color_primary' => '#3b82f6',
                'color_secondary' => '#dbeafe',
            ],
            [
                'name' => 'Números',
                'slug' => 'numeros',
                'description' => 'Números y conceptos numéricos en chino',
                'color_primary' => '#ef4444',
                'color_secondary' => '#fee2e2',
            ],
            [
                'name' => 'Colores',
                'slug' => 'colores',
                'description' => 'Caracteres que representan colores',
                'color_primary' => '#a855f7',
                'color_secondary' => '#f3e8ff',
            ],
        ];

        foreach ($themes as $theme) {
            Theme::create($theme);
        }
    }
}
